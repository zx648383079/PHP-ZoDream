<?php
namespace Module\Shop\Domain\Models;

use Module\Shop\Domain\Entities\GoodsEntity;

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
class GoodsSimpleModel extends GoodsEntity {

    const THUMB_MODE = ['id', 'name', 'series_number', 'thumb', 'price', 'weight', 'stock', 'market_price', 'cat_id', 'brand_id', 'status'];

    protected array $visible = ['id', 'name', 'series_number', 'thumb', 'price', 'weight', 'stock', 'market_price', 'shop', 'url', 'wap_url'];

    protected array $append = ['shop', 'url', 'wap_url'];

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