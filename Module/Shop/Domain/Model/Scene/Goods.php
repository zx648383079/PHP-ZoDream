<?php
namespace Module\Shop\Domain\Model\Scene;

use Module\Shop\Domain\Model\GoodsModel;


/**
 * 列表场景下的商品数据
 */
class Goods extends GoodsModel {

    protected $append = ['shop'];

    protected $visible = ['id', 'name', 'thumb', 'price', 'market_price', 'shop'];

    public function getShopAttribute() {
        return config('app.name');
    }

    public static function query() {
        return parent::query()->select(['id', 'name', 'thumb', 'price', 'market_price']);
    }
}