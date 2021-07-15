<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Exam\Domain\Entities\CourseGradeEntity;
use Module\Exam\Domain\Model\CourseModel;
use Module\Exam\Domain\Model\QuestionModel;
use Zodream\Html\Tree;

class CourseRepository {
    public static function getList(string $keywords = '', int $parent = 0) {
        return CourseModel::query()
            ->where('parent_id', $parent)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
    }

    public static function get(int $id) {
        return CourseModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = CourseModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        CourseModel::where('id', $id)->delete();
    }

    public static function children(int $id) {
        return CourseModel::with('children')
            ->where('parent_id', $id)->get();
    }

    public static function all(bool $full = false) {
        $data = CourseModel::query()->when(!$full, function ($query) {
            $query->select('id', 'name', 'parent_id');
        })->get();
        if ($full) {
            $items = QuestionModel::groupBy('course_id')->selectRaw('COUNT(id) as count,course_id')
                ->pluck('count', 'course_id');
            $data = array_map(function ($item) use ($items) {
                $data = $item->toArray();
                $data['question_count'] = isset($items[$item['id']]) ? intval($items[$item['id']]) : 0;
                return $data;
            }, $data);
        }
        return (new Tree($data))
            ->makeTreeForHtml();
    }

    public static function search(string $keywords = '', int|array $id = 0) {
        return SearchModel::searchOption(
            CourseModel::query(),
            ['name'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }

    public static function gradeAll(int $course) {
        $items = CourseGradeEntity::when($course > 0, function ($query) use ($course) {
                $query->where(function ($query) use ($course) {
                    $query->where('course_id', $course)->orWhere('course_id', 0);
                });
            }, function ($query) use ($course) {
                $query->where('course_id', 0);
            })
            ->orderBy('course_id', 'asc')
            ->orderBy('grade', 'asc')->get();
        $data = [];
        $exist = [];
        foreach ($items as $item) {
            if (in_array($item['grade'], $exist)) {
                continue;
            }
            $data[] = [
                'name' => $item['name'],
                'value' => $item['grade'],
            ];
            $exist[] = $item['grade'];
        }
        usort($data, function ($a, $b) {
            return $a['value'] > $b['value'] ? 1 : -1;
        });
        return $data;
    }

    public static function gradeList(string $keywords = '', int $course = 0) {
        return CourseGradeEntity::with('course')
            ->when($course > 0, function ($query) use ($course) {
                $query->where('course_id', $course);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
    }

    public static function gradeSave(array $data)
    {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        if ($id > 0) {
            $model = CourseGradeEntity::find($id);
        } else {
            $model = CourseGradeEntity::where('course_id', $data['course_id']??0)
                ->where('grade', $data['grade']??1)->first();
        }
        if (empty($model)) {
            $model = new CourseGradeEntity();
        }
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function gradeRemove(int $id)
    {
        CourseGradeEntity::where('id', $id)->delete();
    }

    public static function formatGrade(int $course, int $grade): string {
        if ($grade < 1) {
            return '';
        }
        return CourseGradeEntity::where('grade', $grade)
            ->when($course > 0, function ($query) use ($course) {
                $query->where(function ($query) use ($course) {
                    $query->where('course_id', $course)->orWhere('course_id', 0);
                });
            }, function ($query) use ($course) {
                $query->where('course_id', 0);
            })->orderBy('course_id', 'desc')->value('name') ?? '';
    }
}