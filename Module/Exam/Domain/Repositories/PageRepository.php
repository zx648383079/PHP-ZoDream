<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\Exam\Domain\Model\PageEvaluateModel;
use Module\Exam\Domain\Model\PageModel;
use Module\Exam\Domain\Model\PageQuestionModel;
use Module\Exam\Domain\Model\QuestionModel;
use Zodream\Database\Relation;
use Zodream\Html\Page;

class PageRepository {
    public static function getList(string $keywords = '', int $user = 0, int $course = 0, int $grade = 0) {
        /** @var Page $data */
        $data = PageModel::with('user', 'course')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', auth()->id());
            })->when($course > 0, function ($query) use ($course) {
                $query->where('course_id', $course);
            })->when($grade > 0, function ($query) use ($grade) {
                $query->where('course_grade', $grade);
            })->orderBy('end_at', 'desc')->orderBy('id', 'desc')->page();
        $data->map(function ($item) {
            $data = $item->toArray();
            $data['course_grade_format'] = CourseRepository::formatGrade($item->course_id, $item->course_grade);
            return $data;
        });
        return $data;
    }

    public static function selfList(string $keywords = '') {
        return static::getList($keywords, auth()->id());
    }

    public static function get(int $id, int $user = 0, bool $full = false) {
        $model = PageModel::findOrThrow($id, '数据有误');
        if ($user > 0 && $model->user_id !== $user) {
            throw new \Exception('数据有误');
        }
        $rules = $model->rule_value;
        if ($model->rule_type > 0 && !empty($rules)) {
            return array_merge(
                $model->toArray(),
                [
                    'rule_value' => static::formatQuestion($rules, $full)
                ]
            );
        }
        return $model;
    }

    public static function formatQuestion(array $items, bool $full = false) {
        $query = $full ?  QuestionModel::with('material', 'option_items', 'analysis_items') : QuestionModel::select('id', 'type', 'title');
        $data = Relation::create($items, [
            Relation::MERGE_RELATION_KEY => Relation::make($query, 'id', 'id')
        ]);
        return $full ? array_map(function ($item) {
            $item['editable'] = $item['user_id'] == auth()->id();
            return $item;
        }, $data) : $data;
    }

    public static function getSelf(int $id) {
        return static::get($id, auth()->id(), true);
    }

    public static function save(array $data, int $user = 0) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = PageModel::findOrNew($id);
        if ($id > 0 && $user > 0 && $model->user_id !== $user) {
            throw new \Exception('无法保存');
        }
        $model->load($data);
        $model->user_id = auth()->id();
        $model->score = 0;
        $model->question_count = 0;
        foreach ($model->rule_value as $item) {
            $model->score += intval($item['score']);
            $model->question_count += $model->rule_type < 1 ? intval($item['amount']) : 1;
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function selfSave(array $data) {
        $items = isset($data['question_items']) && !empty($data['question_items']) ? $data['question_items'] : [];
        if (!empty($items)) {
            $data['rule_type'] = 1;
            $data['rule_value'] = '';
        }
        $model = static::save($data, auth()->id());
        if (empty($items)) {
            return $model;
        }
        $idItems = [];
        $model->question_count = 0;
        $model->score = 0;
        foreach ($items as $item) {
            $qId = isset($item['id']) ? intval($item['id']) : 0;
            try {
                $q = QuestionRepository::selfSave($item);
                if ($q) {
                    $qId = $q->id;
                }
            } catch (\Exception $ex) {}
            $score = intval($item['score']);
            if ($qId > 0) {
                $model->question_count ++;
                $model->score += $score;
                $idItems[] = [
                    'id' => $qId,
                    'score' => $score,
                ];
            }
        }
        $model->rule_value = $idItems;
        $model->save();
        return array_merge(
            $model->toArray(),
            [
                'rule_value' => static::formatQuestion($idItems, true)
            ]
        );
    }

    public static function remove(int $id, int $user = 0) {
        $model = PageModel::where('id', $id)->when($user > 0, function ($query) use ($user) {
            $query->where('user_id', $user);
        })->first();
        if (empty($model)) {
            throw new \Exception('无权限删除');
        }
        $model->delete();
        PageQuestionModel::where('page_id', $id)->delete();
        PageEvaluateModel::where('page_id', $id)->delete();
    }

    public static function selfRemove(int $id) {
        static::remove($id, auth()->id());
    }

    public static function evaluateList(int $id, string $keywords = '') {
        return PageEvaluateModel::with('user')
            ->where('page_id', $id)
            ->when(!empty($keywords), function ($query) use ($id, $keywords) {
                $users = UserRepository::searchUserId($keywords,
                    PageEvaluateModel::where('page_id', $id)
                        ->selectRaw('DISTINCT user_id')
                        ->pluck('user_id'), true);
                if (empty($users)) {
                    return $query->isEmpty();
                }
                $query->whereIn('user_id', $users);
            })
            ->orderBy('created_at', 'desc')->page();
    }

    public static function evaluateRemove(int $id, int $user = 0) {
        $model = PageEvaluateModel::findOrThrow($id, '无权限删除');
        if ($user > 0 && !static::can($model->page_id)) {
            throw new \Exception('无权限删除');
        }
        $model->delete();
    }

    public static function selfEvaluateRemove(int $id) {
        static::evaluateRemove($id, auth()->id());
    }

    public static function can(int $id) {
        return PageModel::where('id', $id)->where('user_id', auth()->id())->count() > 0;
    }

    public static function suggestion(string $keywords) {
        return PageModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->orderBy('end_at', 'desc')->limit(5)->get();
    }
}