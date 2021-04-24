<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Domain\Repositories;


use Domain\Model\SearchModel;
use Module\OpenPlatform\Domain\Model\PlatformModel;

class PlatformRepository {

    public static function getList(string $keywords = '') {
        return PlatformModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->orderBy('status', 'desc')->page();
    }

    public static function get(int $id) {
        return PlatformModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = PlatformModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        PlatformModel::where('id', $id)->delete();
    }
}