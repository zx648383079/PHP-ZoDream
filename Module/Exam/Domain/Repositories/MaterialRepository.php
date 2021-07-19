<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Exam\Domain\Model\QuestionMaterialModel;

class MaterialRepository {
    public static function getList(string $keywords = '', int $course = 0, bool $full = false) {
        $query = $full ? QuestionMaterialModel::withCount('question') : QuestionMaterialModel::query();
        return $query
            ->when($course > 0, function ($query) use ($course) {
                $query->where('course_id', $course);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->page();
    }

    public static function get(int $id) {
        return QuestionMaterialModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = QuestionMaterialModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        QuestionMaterialModel::where('id', $id)->delete();
    }
}