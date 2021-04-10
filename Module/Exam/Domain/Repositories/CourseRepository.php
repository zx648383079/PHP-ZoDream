<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Exam\Domain\Model\CourseModel;
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
        return (new Tree(CourseModel::query()->when(!$full, function ($query) {
            $query->select('id', 'name', 'parent_id');
        })->get()))
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
}