<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\BrandModel;

class BrandRepository {

    public static function getList() {
        return BrandModel::query()->get();
    }

    public static function findOrNew($name) {
        if (empty($name)) {
            return 0;
        }
        $id = BrandModel::query()->where('name', $name)->value('id');
        if ($id > 0) {
            return $id;
        }
        return BrandModel::query()->insert([
            'name' => $name
        ]);
    }
}