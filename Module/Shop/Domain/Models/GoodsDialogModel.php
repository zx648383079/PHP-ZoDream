<?php
namespace Module\Shop\Domain\Models;

/**
 * 列表场景下的商品数据
 */
class GoodsDialogModel extends GoodsModel {

    const THUMB_MODE = [
        'id', 'cat_id',
        'brand_id',
        'name',
        'series_number',
        'thumb',
        'picture',
        'brief',
        'price',
        'market_price',
        'stock',
        'attribute_group_id',
        'weight',
        'sales',
        'is_best',
        'is_hot',
        'is_new',
        'status',
        'type',];

    public static function query() {
        return parent::query()->select(self::THUMB_MODE);
    }
}