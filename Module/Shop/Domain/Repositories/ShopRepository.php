<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;

class ShopRepository {
    public static function siteInfo() {
        return [
            'name' => '聚百客综合商店',
            'version' => '0.1',
            'logo' => url()->asset('assets/upload/image/shop_logo.png'),
            'category' => CategoryModel::query()->count(),
            'brand' => BrandModel::query()->count(),
            'goods' => GoodsModel::query()->count(),
            'currency' => '￥',
        ];
    }
}