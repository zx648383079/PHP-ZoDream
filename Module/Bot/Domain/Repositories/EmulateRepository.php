<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Bot\Domain\Adapters\Emulate\EmulateAdapter;
use Module\Bot\Domain\Model\BotModel;
use Module\Bot\Domain\Model\BotSimpleModel;
use Module\Bot\Domain\Model\MediaModel;

class EmulateRepository {

    public static function getList(string $keywords = '') {
        return BotSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'account']);
        })->where('status', '>', BotModel::STATUS_DELETED)->page();
    }

    public static function get(int $id) {
        $model = BotSimpleModel::where('id', $id)->where('status', '>', BotModel::STATUS_DELETED)
            ->first();
        if (empty($model)) {
            throw new \Exception('公众号不存在');
        }
        $model->menu_list = MenuRepository::getList($model->id);
        $model->news_list = MediaModel::where('bot_id', $model->id)->where('type', MediaModel::TYPE_NEWS)
            ->orderBy('created_at', 'desc')->limit(6)
            ->select('id', 'title', 'type', 'media_id', 'parent_id', 'thumb', 'created_at')
            ->get();
        return $model;
    }

    public static function reply(int $id) {
        $model = BotModel::findOrThrow($id);
        if ($model->status !== BotModel::STATUS_ACTIVE &&
                $model->status !== BotModel::STATUS_INACTIVE) {
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
        $model->account = BotSimpleModel::find($model->bot_id);
        return $model;
    }
}