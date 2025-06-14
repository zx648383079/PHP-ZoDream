<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Exam\Domain\Model\QuestionAnalysisModel;
use Module\Exam\Domain\Model\QuestionAnswerModel;
use Module\Exam\Domain\Model\QuestionModel;
use Module\Exam\Domain\Model\QuestionOptionModel;

class QuestionRepository {
    public static function getList(string $keywords = '', int $course = 0, int $user = 0,
                                   int $grade = 0, int $material = 0, bool $filter = false) {
        $data = QuestionModel::with('course', 'user')
            ->when($course > 0, function ($query) use ($course) {
                $query->where('course_id', $course);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->when($material > 0, function ($query) use ($material) {
                $query->where('material_id', $material);
            })->when($grade > 0, function ($query) use ($grade) {
                $query->where('course_grade', $grade);
            })->when($filter, function ($query) {
                $query->where('type', '<', 5);
            })->orderBy('id', 'desc')->page();
        $data->map(function ($item) {
            $data = $item->toArray();
            $data['course_grade_format'] = CourseRepository::formatGrade($item->course_id, $item->course_grade);
            return $data;
        });
        return $data;
    }

    public static function searchList(string $keywords = '',
                                      int $course = 0, int $user = 0, int $grade = 0, bool $full = false) {
        $query = $full ? QuestionModel::with('course', 'user', 'option_items', 'analysis_items') :
            QuestionModel::with('course', 'user')
            ->select('id', 'title', 'course_id', 'user_id', 'type', 'easiness', 'created_at');
        $data = $query
            ->when($course > 0, function ($query) use ($course) {
                $query->where('course_id', $course);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->when($grade > 0, function ($query) use ($grade) {
                $query->where('course_grade', $grade);
            })->orderBy('id', 'desc')->page();
        if ($full) {
            $data->map(function ($item) {
                $item['editable'] = $item->user_id === auth()->id();
                return $item;
            });
        }
        return $data;
    }

    public static function selfList(string $keywords = '', int $course = 0) {
        return static::getList($keywords, $course, auth()->id());
    }

    public static function get(int $id) {
        return QuestionModel::findOrThrow($id, '数据有误');
    }

    public static function getFull(int $id, int $user = 0) {
        $model = static::get($id);
        if ($user > 0 && $model->user_id !== $user) {
            throw new \Exception('数据有误');
        }
        return self::formatItem($model);
    }

    private static function formatItem(QuestionModel $model) {
        $data = $model->toArray();
        $data['option_items'] = $model->option_items;
        $data['analysis_items'] = $model->analysis_items;
        if ($model->material_id > 0) {
            $data['material'] = $model->material;
        }
        if ($model->type == 5) {
            $data['children'] = array_map(function ($item) {
                return self::formatItem($item);
            }, QuestionModel::where('parent_id', $model->id)
                ->get());
        }
        return $data;
    }

    public static function selfFull(int $id) {
        return static::getFull($id, auth()->id());
    }

    public static function save(array $data, int $user = 0, bool $check = false, bool $addKid = true) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $isLarge = isset($data['type']) && $data['type'] == 5;
        if ($check && $id < 1 && static::checkRepeat($data)) {
            throw new \Exception('请不要重复添加');
        }
        if ($isLarge && (!isset($data['children']) || empty($data['children']))) {
            throw new \Exception('大题下面必须包含小题');
        }
        if ((!isset($data['material_id']) || $data['material_id'] < 0) && isset($data['material']) && !empty($data['material'])) {
            $material = MaterialRepository::save($data['material']);
            $data['material_id'] = $material->id;
        }
        $model = QuestionModel::findOrNew($id);
        if ($id > 0 && $user > 0 && $model->user_id !== $user) {
            throw new \Exception('无法保存');
        }
        unset($data['updated_at'], $data['created_at']);
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        if (!$isLarge && isset($data['option_items'])) {
            QuestionOptionModel::batchSave($model, $data['option_items']);
        }
        if (isset($data['analysis_items'])) {
            QuestionAnalysisModel::batchSave($model, $data['analysis_items']);
        }
        if ($addKid && $isLarge) {
            foreach ($data['children'] as $item) {
                $item['parent_id'] = $model->id;
                $item['course_id'] = $model->course_id;
                $item['course_grade'] = $model->course_grade;
                $item['easiness'] = $model->easiness;
                $item['dynamic'] = $data['dynamic'] ?? '';
                $itemType = isset($item['type']) ? intval($item['type']) : 0;
                if ($itemType === 4 && empty($item['content'])) {
                    $item['content'] = $item['title'];
                }
                static::save($item, $user);
            }
        }
        return $model;
    }

    public static function checkRepeat(array $data): bool {
        $userId = auth()->id();
        return QuestionModel::where('type', $data['type'] ?? 0)
            ->where('title', $data['title'])
            ->when(isset($data['course_id']) && $data['course_id'] > 0, function ($query) use ($data) {
                $query->where('course_id', $data['course_id']);
            })
            ->when(isset($data['content']), function ($query) use ($data) {
                $query->where('content', $data['content']);
            })
            ->when(isset($data['image']), function ($query) use ($data) {
                $query->where('image', $data['image']);
            })
            ->where('user_id', $userId)
            ->count() > 0;
    }

    public static function selfSave(array $data, bool $addKid = true) {
        if (isset($data['material'])) {
            $m = MaterialRepository::save($data['material']);
            $data['material_id'] = $m->id;
        }
        return static::save($data, auth()->id(), false, $addKid);
    }

    public static function remove(int $id, int $user = 0) {
        $model = QuestionModel::where('id', $id)->when($user > 0, function ($query) use ($user) {
            $query->where('user_id', $user);
        })->first();
        if (empty($model)) {
            throw new \Exception('无权限删除');
        }
        $model->delete();
        $idItems = [$id];
        if ($model->type == 5) {
            $idItems = array_merge($idItems, QuestionModel::where('parent_id', $model->id)
                ->pluck('id'));
        }
        QuestionAnswerModel::whereIn('question_id', $idItems)->delete();
        QuestionOptionModel::whereIn('question_id', $idItems)->delete();
        QuestionAnalysisModel::whereIn('question_id', $idItems)->delete();
    }

    public static function selfRemove(int $id) {
        static::remove($id, auth()->id());
    }

    public static function search(string $keywords = '', int|array $id = 0) {
        return SearchModel::searchOption(
            QuestionModel::query(),
            ['title'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }

    public static function check(string $title, int $id = 0) {
        return QuestionModel::with('course', 'user')->where('title', $title)->where('id', '<>', $id)->orderBy('id', 'desc')->get();
    }

    public static function suggestion(string $keywords, int $course = 0) {
        return QuestionModel::with('course')
                ->when($course > 0, function ($query) use ($course) {
                    $query->where('course_id', $course);
                })
                ->when(!empty($keywords), function ($query) {
                    SearchModel::searchWhere($query, ['title']);
                })->orderBy('id', 'desc')->limit(5)->get('id', 'title', 'course_id', 'type', 'easiness');
    }

    public static function crawlSave(array $data) {
        return self::save($data);
    }
}