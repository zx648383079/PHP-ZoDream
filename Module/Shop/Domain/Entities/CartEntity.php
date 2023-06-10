<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Entities;

use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $type
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $product_id
 * @property integer $amount
 * @property float $price
 * @property integer $is_checked
 * @property integer $selected_activity
 * @property string $attribute_id
 * @property string $attribute_value
 * @property integer $expired_at
 */
class CartEntity extends Entity {

    public static function tableName() {
        return 'shop_cart';
    }

    protected function rules() {
        return [
            'type' => 'int:0,127',
            'user_id' => 'required|int',
            'goods_id' => 'required|int',
            'product_id' => 'int',
            'amount' => 'int',
            'price' => 'required|string',
            'is_checked' => 'int:0,127',
            'selected_activity' => 'int',
            'attribute_id' => 'string:0,255',
            'attribute_value' => 'string:0,255',
            'expired_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => 'Type',
            'user_id' => 'User Id',
            'goods_id' => 'Goods Id',
            'product_id' => 'Product Id',
            'amount' => 'Amount',
            'price' => 'Price',
            'is_checked' => 'Is Checked',
            'selected_activity' => 'Selected Activity',
            'attribute_id' => 'Attribute Id',
            'attribute_value' => 'Attribute Value',
            'expired_at' => 'Expired At',
        ];
    }
}