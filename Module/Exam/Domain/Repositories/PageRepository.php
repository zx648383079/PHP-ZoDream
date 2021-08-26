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
        $query = $full ? QuestionModel::with('material', 'option_items', 'analysis_items') : QuestionModel::select('id', 'type', 'title');
        $data = Relation::create($items, [
            Relation::MERGE_RELATION_KEY => Relation::make($query, 'id', 'id')
        ]);
        if (!$full) {
            return $data;
        }
        $parentItems = [];
        foreach ($data as $i => $item) {
            $data[$i]['editable'] = $item['editable'] = $item['user_id'] === auth()->id();
            if ($item['parent_id'] < 1) {
                continue;
            }
            if (!isset($parentItems)) {
                $parentItems[$item['parent_id']] = [];
            }
            $parentItems[$item['parent_id']][] = $item;
        }
        if (empty($parentItems)) {
            return $data;
        }
        $sourceItems = QuestionModel::with('material', 'analysis_items')
            ->whereIn('id', array_keys($parentItems))->get();
        $distItems = [];
        foreach ($data as $item) {
            if ($item['parent_id'] < 1) {
                $distItems[] = $item;
                continue;
            }
            if (!isset($parentItems[$item['parent_id']])) {
                continue;
            }
            foreach ($sourceItems as $q) {
                if ($q->id == $item['parent_id']) {
                    $res = $q->toArray();
                    $res['children'] = $parentItems[$item['parent_id']];
                    $distItems[] = $res;
                    unset($parentItems[$item['parent_id']]);
                    continue;
                }
            }
        }
        return $distItems;
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
        unset($data['updated_at'], $data['created_at']);
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
            $qItems = static::saveQuestion($item);
            foreach ($qItems as $q) {
                $model->question_count ++;
                $model->score += $q['score'];
                $idItems[] = $q;
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

    private static function saveQuestion(array $data): array {
        $qId = isset($data['id']) ? intval($data['id']) : 0;
        $score = intval($data['score']);
        $q = null;
        try {
            $q = QuestionRepository::selfSave($data, false);
            if ($q) {
                $qId = $q->id;
            }
        } catch (\Exception $ex) {}
        if ($qId < 1) {
            return [];
        }
        if (!isset($data['type']) || $data['type'] != 5) {
            return [['id' => $qId, 'score' => $score]];
        }
        if (empty($q)) {
            $q = QuestionModel::find($qId);
        }
        $items = [];
        foreach ($data['children'] as $item) {
            $item['parent_id'] = $qId;
            $item['course_id'] = $q->course_id;
            $data['course_grade'] = $q->course_grade;
            $data['easiness'] = $q->easiness;
            $data['dynamic'] = $data['dynamic'] ?? '';
            $itemType = isset($item['type']) ? intval($item['type']) : 0;
            if ($itemType === 4 && empty($item['content'])) {
                $item['content'] = $item['title'];
            }
            try {
                $kid = QuestionRepository::selfSave($item);
                $items[] = [
                    'id' => $kid->id,
                    'score' => intval($item['score'])
                ];
            } catch (\Exception $ex) {}
        }
        return $items;
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
        PageQuestionModel::where('evaluate_id', $id)->delete();
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

    public static function evaluateDetail(int $id) {
        $model = PageEvaluateModel::findOrThrow($id, '不存在');
        $data = $model->toArray();
        /** @var PageQuestionModel[] $items */
        $items = PageQuestionModel::with('question')->where('evaluate_id', $id)->orderBy('id', 'asc')->get();
        $data['user'] = $model->user;
        $data['page'] = PageModel::where('id', $model->page_id)->first();
        $data['data'] = [];
        foreach ($items as $i => $item) {
            $data['data'][] = $item->format($i + 1, $model->status > 0);
        }
        return $data;
    }
}