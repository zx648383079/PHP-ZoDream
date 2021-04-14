<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Contact\Domain\Model\FeedbackModel;

class FeedbackRepository {
    public static function getList(string $keywords = '') {
        return FeedbackModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'email', 'content']);
        })->orderBy([
            'status' => 'asc',
            'id' => 'desc'
        ])->page();
    }

    public static function get(int $id) {
        return FeedbackModel::findOrThrow($id, '数据有误');
    }

    public static function change(int $id, int $status) {
        $model = static::get($id);
        $model->status = $status;
        $model->save();
        return $model;
    }

    public static function remove(int $id) {
        FeedbackModel::where('id', $id)->delete();
    }
}