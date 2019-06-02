<?php
namespace Module\Shop\Domain\Models;

use Module\Shop\Domain\Entities\GoodsEntity;

/**
 * 列表场景下的商品数据
 */
class GoodsSimpleModel extends GoodsEntity {

    const THUMB_MODE = ['id', 'name', 'series_number', 'thumb', 'price', 'market_price', 'cat_id', 'brand_id'];

    protected $visible = ['id', 'name', 'series_number', 'thumb', 'price', 'market_price', 'shop'];

    public static function query() {
        return parent::query()->select(self::THUMB_MODE);
    }
}