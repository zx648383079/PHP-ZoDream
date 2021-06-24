<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Module\Exam\Domain\Model\PageEvaluateModel;
use Module\Exam\Domain\Model\PageModel;
use Module\Exam\Domain\Model\PageQuestionModel;

class EvaluateRepository {
    public static function getList(string $keywords = '', int $user = 0) {
        return PageEvaluateModel::when(!empty($keywords), function ($query) {
                // SearchModel::searchWhere($query, ['title']);
            })->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->orderBy('id', 'desc')->page();
    }

    public static function selfList(string $keywords = '') {
        return static::getList($keywords, auth()->id());
    }

    public static function get(int $id, int $user = 0) {
        $model = PageEvaluateModel::findOrThrow($id, '数据错误');
        if ($user > 0 && $model->user_id !== $user) {
            throw new \Exception('数据错误');
        }
        $model->page = PageModel::find($model->page_id);
        $model->question_items = PageQuestionModel::where('evaluate_id', $model->id)->get();
        return $model;
    }

    public static function getSelf(int $id) {
        return static::get($id, auth()->id());
    }
}