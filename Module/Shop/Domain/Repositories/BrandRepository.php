<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\GoodsModel;

class BrandRepository {

    public static function getList() {
        return BrandModel::query()->get();
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