<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\WeChat\Domain\Adapters\EmulateAdapter;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Model\WeChatSimpleModel;

class EmulateRepository {

    public static function getList(string $keywords = '') {
        return WeChatSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'account']);
        })->where('status', '>', WeChatModel::STATUS_DELETED)->page();
    }

    public static function get(int $id) {
        $model = WeChatSimpleModel::where('id', $id)->where('status', '>', WeChatModel::STATUS_DELETED)
            ->first();
        if (empty($model)) {
            throw new \Exception('公众号不存在');
        }
        $model->menu_list = MenuRepository::getList($model->id);
        $model->news_list = MediaModel::where('wid', $model->id)->where('type', MediaModel::TYPE_NEWS)
            ->orderBy('created_at', 'desc')->limit(6)
            ->select('id', 'title', 'type', 'media_id', 'parent_id', 'thumb', 'created_at')
            ->get();
        return $model;
    }

    public static function reply(int $id) {
        $model = WeChatModel::findOrThrow($id);
        if ($model->status !== WeChatModel::STATUS_ACTIVE &&
                $model->status !== WeChatModel::STATUS_INACTIVE) {
            throw new \Exception('error platform');
        }
        $adapter = new EmulateAdapter($model);
        return $adapter->listenWithBack();
    }

    public static function media(int $id) {
        $model = MediaModel::find($id);
        if (empty($model)) {
            throw new \Exception('资源不存在');
        }
        $model->account = WeChatSimpleModel::find($model->wid);
        return $model;
    }
}