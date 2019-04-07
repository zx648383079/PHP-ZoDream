<?php
namespace Module\Shop\Domain\Models\Scene;

use Module\Shop\Domain\Models\GoodsModel;


/**
 * 列表场景下的商品数据
 */
class Goods extends GoodsModel {



    protected $visible = ['id', 'name', 'series_number', 'thumb', 'price', 'market_price', 'shop'];



    public static function query() {
        return parent::query()->select(self::THUMB_MODE);
    }
}