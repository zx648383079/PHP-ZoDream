<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Exam\Domain\Model\PageEvaluateModel;
use Module\Exam\Domain\Model\PageModel;
use Module\Exam\Domain\Model\PageQuestionModel;

class PageRepository {
    public static function getList(string $keywords = '') {
        return PageModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->orderBy('end_at', 'desc')->page();
    }

    public static function get(int $id) {
        return PageModel::findOrThrow($id, '数据有误');
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
}