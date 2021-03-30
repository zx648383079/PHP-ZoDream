<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\SEO\Domain\Model\AgreementModel;

class AgreementRepository{

    public static function getList(string $keywords = '') {
        return AgreementModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'title']);
        })->page();
    }

    public static function get(int $id) {
        return AgreementModel::findOrThrow($id, '服务协议不存在');
    }

    public static function getByName(string $name) {
        $model = AgreementModel::where('name', $name)
            ->where('status', 1)
            ->orderBy('id', 'desc')->first();
        if (empty($model)) {
            throw new \Exception('服务协议不存在');
        }
        return $model;
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = AgreementModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        AgreementModel::where('id', $id)->delete();
    }
}