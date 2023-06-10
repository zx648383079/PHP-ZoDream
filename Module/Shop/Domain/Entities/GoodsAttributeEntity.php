<?php
namespace Module\Shop\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class AttributeEntity
 * @package Module\Shop\Domain\Entities
 * @property integer $id
 * @property integer $goods_id
 * @property integer $attribute_id
 * @property string $value
 * @property float $price
 */
class GoodsAttributeEntity extends Entity {

    public static function tableName() {
        return 'shop_goods_attribute';
    }
    protected function rules() {
        return [
            'goods_id' => 'int',
            'attribute_id' => 'required|int',
            'value' => 'required|string:0,255',
            'price' => 'string',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'goods_id' => 'Goods Id',
            'attribute_id' => 'Attribute Id',
            'value' => 'Value',
            'price' => 'Price',
        ];
    }
}