<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\WeChat\Domain\Model\QrcodeModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\ThirdParty\WeChat\Account;

class QrcodeRepository {
    public static function getList(int $wid, string $keywords = '') {
        AccountRepository::isSelf($wid);
        return QrcodeModel::where('wid', $wid)->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->page();
    }

    public static function get(int $id) {
        return QrcodeModel::findOrThrow($id, '数据有误');
    }

    public static function getSelf(int $id) {
        $model = static::get($id);
        AccountRepository::isSelf($model->wid);
        return $model;
    }

    public static function remove(int $id) {
        $model = static::getSelf($id);
        $model->delete();
    }

    public static function save(int $wid, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id'], $data['wid']);
        if ($id > 0) {
            $model = static::getSelf($id);
        } else {
            $model = new QrcodeModel();
            $model->wid = $wid;
            AccountRepository::isSelf($model->wid);
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
        $data = PlatformRepository::entry($model->wid)
            ->pushQr($model);
        $model->set($data);
        $model->save();
    }
}