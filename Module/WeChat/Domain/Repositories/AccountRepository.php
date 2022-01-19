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

    public static function selfList(string $keywords = '') {
        return WeChatModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'account']);
        })->where('user_id', auth()->id())->page();
    }

    public static function get(int $id) {
        return WeChatModel::findOrThrow($id, '数据有误');
    }

    public static function getSelf(int $id) {
        $model = WeChatModel::where('id', $id)->where('user_id', auth()->id())->first();
        if (empty($model)) {
            throw new  \Exception('数据有误');
        }
        return $model;
    }

    public static function remove(int $id) {
        WeChatModel::where('id', $id)->where('user_id', auth()->id())->delete();
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? self::getSelf($id) : new WeChatModel();;
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function isSelf(int $id) {
        $count = WeChatModel::where('id', $id)->where('user_id', auth()->id())->count();
        if ($count < 1) {
            throw new \Exception('无权限管理');
        }
        return true;
    }
}