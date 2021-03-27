<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Exam\Domain\Model\QuestionAnswerModel;
use Module\Exam\Domain\Model\QuestionModel;
use Module\Exam\Domain\Model\QuestionOptionModel;

class QuestionRepository {
    public static function getList(string $keywords = '', int $course = 0) {
        return QuestionModel::with('course')
            ->when($course > 0, function ($query) use ($course) {
                $query->where('course_id', $course);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->where('parent_id', 0)->orderBy('id', 'desc')->page();
    }

    public static function get(int $id) {
        return QuestionModel::findOrThrow($id, '数据有误');
    }

    public static function getFull(int $id) {
        $model = static::get($id);
        $model->option = QuestionOptionModel::where('question_id', $model->id)
            ->orderBy('id', 'asc')->get();
        return $model;
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = QuestionModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        if (isset($data['option'])) {
            QuestionOptionModel::batchSave($model, $data['option']);
        }
        return $model;
    }

    public static function remove(int $id) {
        QuestionModel::where('id', $id)->delete();
        QuestionAnswerModel::where('question_id', $id)->delete();
        QuestionOptionModel::where('question_id', $id)->delete();
    }

    public static function search(string $keywords = '', int|array $id = 0) {
        return SearchModel::searchOption(
            QuestionModel::query(),
            ['title'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }
}