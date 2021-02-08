<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\GoodsModel;

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

    public static function recommend() {
        $items = BrandModel::query()->limit(4)->get();
        foreach ($items as $item) {
            $item['price'] = GoodsModel::where('brand_id', $item->id)
                ->min('price');
            $item['image'] = $item->logo;
        }
        return $items;
    }
}