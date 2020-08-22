<?php
namespace Module\Shop\Domain\Models;

/**
 * 列表场景下的商品数据
 * @property integer $id
 * @property integer $cat_id
 * @property integer $brand_id
 * @property string $name
 * @property string $series_number
 * @property string $keywords
 * @property string $thumb
 * @property string $picture
 * @property string $description
 * @property string $brief
 * @property string $content
 * @property float $price
 * @property float $market_price
 * @property integer $stock
 * @property integer $attribute_group_id
 * @property float $weight
 * @property integer $shipping_id
 * @property integer $is_best
 * @property integer $is_hot
 * @property integer $is_new
 * @property integer $status
 * @property integer $type
 * @property string $admin_note
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
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