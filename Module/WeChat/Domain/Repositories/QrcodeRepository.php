<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\WeChat\Domain\Model\QrcodeModel;

class QrcodeRepository {
    public static function getList(int $wid, string $keywords = '') {
        return QrcodeModel::where('wid', $wid)->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->page();
    }

    public static function get(int $id) {
        return QrcodeModel::findOrThrow($id, '数据有误');
    }

    public static function remove(int $id) {
        QrcodeModel::where('id', $id)->delete();
    }

    public static function save(int $wid, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = QrcodeModel::findOrNew($id);
        $model->load($data);
        $model->wid = $wid;
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }
}