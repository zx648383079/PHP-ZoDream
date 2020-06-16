<?php
namespace Module\Shop\Domain\Models;

use Module\Shop\Domain\Entities\GoodsEntity;

/**
 * 列表场景下的商品数据
 */
class GoodsSimpleModel extends GoodsEntity {

    const THUMB_MODE = ['id', 'name', 'series_number', 'thumb', 'price', 'weight', 'stock', 'market_price', 'cat_id', 'brand_id', 'status'];

    protected $visible = ['id', 'name', 'series_number', 'thumb', 'price', 'weight', 'stock', 'market_price', 'shop', 'url', 'wap_url'];

    protected $append = ['shop', 'url', 'wap_url'];

    public function getUrlAttribute() {
        return url('./goods', ['id' => $this->id]);
    }

    public function getWapUrlAttribute() {
        return url('./mobile/goods', ['id' => $this->id]);
    }


    public static function query() {
        return parent::query()->select(self::THUMB_MODE);
    }
}