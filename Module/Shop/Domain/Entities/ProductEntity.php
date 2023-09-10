<?php
namespace Module\Shop\Domain\Entities;

use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $goods_id
 * @property float $price
 * @property float $market_price
 * @property integer $stock
 * @property float $weight
 * @property string $series_number
 * @property string $attributes
 */
class ProductEntity extends Entity {

    public static function tableName(): string {
        return 'shop_product';
    }

    protected function rules(): array {
        return [
            'goods_id' => 'required|int',
            'price' => 'string',
            'market_price' => 'string',
            'stock' => 'int',
            'weight' => 'string',
            'series_number' => 'string:0,50',
            'attributes' => 'string:0,100',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'goods_id' => 'Goods Id',
            'price' => 'Price',
            'market_price' => 'Market Price',
            'stock' => 'Stock',
            'weight' => 'Weight',
            'series_number' => 'Series Number',
            'attributes' => 'Attributes',
        ];
    }

}