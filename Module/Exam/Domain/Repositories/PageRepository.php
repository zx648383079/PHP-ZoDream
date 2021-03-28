<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\Exam\Domain\Model\PageEvaluateModel;
use Module\Exam\Domain\Model\PageModel;
use Module\Exam\Domain\Model\PageQuestionModel;
use Module\Exam\Domain\Model\QuestionModel;

class PageRepository {
    public static function getList(string $keywords = '') {
        return PageModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->orderBy('end_at', 'desc')->page();
    }

    public static function get(int $id) {
        $model = PageModel::findOrThrow($id, '数据有误');
        $rules = $model->rule_value;
        if ($model->rule_type > 0 && !empty($rules)) {
            $model->rule_value = QuestionModel::whereIn('id', $rules)
                ->get('id', 'type', 'title');
        }
        return $model;
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = PageModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        PageModel::where('id', $id)->delete();
        PageQuestionModel::where('page_id', $id)->delete();
        PageEvaluateModel::where('page_id', $id)->delete();
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

    public static function evaluateRemove(int $id) {
        PageEvaluateModel::where('id', $id)->delete();
    }
}