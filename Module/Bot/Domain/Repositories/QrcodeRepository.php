<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Bot\Domain\Model\QrcodeModel;
use Module\Bot\Domain\Model\BotModel;
use Zodream\ThirdParty\WeChat\Account;

class QrcodeRepository {
    public static function getList(int $bot_id, string $keywords = '') {
        AccountRepository::isSelf($bot_id);
        return QrcodeModel::where('bot_id', $bot_id)->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->page();
    }

    public static function manageList(int $bot_id = 0, string $keywords = '') {
        return QrcodeModel::when($bot_id > 0, function ($query) use ($bot_id) {
            $query->where('bot_id', $bot_id);
        })->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->page();
    }

    public static function get(int $id) {
        return QrcodeModel::findOrThrow($id, '数据有误');
    }

    public static function getSelf(int $id) {
        $model = static::get($id);
        AccountRepository::isSelf($model->bot_id);
        return $model;
    }

    public static function remove(int $id) {
        $model = static::getSelf($id);
        $model->delete();
    }

    public static function save(int $bot_id, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id'], $data['bot_id']);
        if ($id > 0) {
            $model = static::getSelf($id);
        } else {
            $model = new QrcodeModel();
            $model->bot_id = $bot_id;
            AccountRepository::isSelf($model->bot_id);
        }
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        if (empty($model->url)) {
            static::async($model);
        }
        return $model;
    }

    public static function async(QrcodeModel $model) {
        $data = BotRepository::entry($model->bot_id)
            ->pushQr($model);
        $model->set($data);
        $model->save();
    }
}