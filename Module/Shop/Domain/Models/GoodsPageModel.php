<?php
namespace Module\Shop\Domain\Models;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */

use Module\Shop\Domain\Entities\GoodsEntity;

/**
 * Class GoodsModel
 * @package Domain\Model\Shopping
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
 * @property bool $is_best
 * @property bool $is_hot
 * @property bool $is_new
 * @property integer $status
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property AttributeModel[] $static_properties
 * @property AttributeModel[] $properties
 */
class GoodsPageModel extends GoodsEntity {



    protected array $visible = ['id', 'name', 'series_number', 'thumb', 'price',
        'market_price', 'shop', 'category', 'brand', 'is_best',
        'is_new', 'is_hot', 'stock', 'attribute_group_id'];

    protected array $append = ['category', 'brand'];

    public function category() {
        return $this->hasOne(CategorySimpleModel::class, 'id', 'cat_id');
    }

    public function brand() {
        return $this->hasOne(BrandSimpleModel::class, 'id', 'brand_id');
    }

    public static function query() {
        return parent::query()->select(['id', 'name', 'series_number', 'thumb',
            'price', 'market_price', 'cat_id', 'brand_id',
            'is_best', 'is_new', 'is_hot', 'stock', 'attribute_group_id']);
    }
}