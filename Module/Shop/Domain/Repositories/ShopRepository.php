<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Module\SEO\Domain\Option;
use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;

class ShopRepository {

    public static function isOpen(): bool {
        if (app()->isDebug()) {
            return true;
        }
        return !!Option::value('shop_open_status', false);
    }

    public static function siteInfo(): array {
        return [
            'name' => '聚百客综合商店',
            'version' => '0.1',
            'logo' => url()->asset('assets/upload/image/shop_logo.png'),
            'qr' => url()->asset('assets/images/wx.jpg'),
            'category' => CategoryModel::query()->count(),
            'brand' => BrandModel::query()->count(),
            'goods' => GoodsModel::query()->count(),
            'currency' => '￥',
        ];
    }
}