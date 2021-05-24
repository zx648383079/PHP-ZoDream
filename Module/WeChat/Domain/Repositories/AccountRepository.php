<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\WeChat\Domain\Model\WeChatModel;

class AccountRepository {
    public static function getList(string $keywords = '') {
        return WeChatModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'account']);
        })->page();
    }

    public static function get(int $id) {
        return WeChatModel::findOrThrow($id, '数据有误');
    }

    public static function remove(int $id) {
        WeChatModel::where('id', $id)->delete();
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = WeChatModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }
}