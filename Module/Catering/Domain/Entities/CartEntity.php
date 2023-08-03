<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 用户购物车
 * @property integer $id
 * @property integer $type
 * @property integer $user_id
 * @property integer $store_id
 * @property integer $goods_id
 * @property integer $amount
 * @property float $price
 * @property float $discount
 * @property integer $updated_at
 * @property integer $created_at
 */
class CartEntity extends Entity {
    public static function tableName() {
        return 'eat_cart';
    }

    protected function rules() {
        return [
            'type' => 'int:0,127',
            'user_id' => 'required|int',
            'store_id' => 'required|int',
            'goods_id' => 'required|int',
            'amount' => 'required|int',
            'price' => 'required|string',
            'discount' => 'required|string',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => 'Type',
            'user_id' => 'User Id',
            'store_id' => 'Store Id',
            'goods_id' => 'Goods Id',
            'amount' => 'Amount',
            'price' => 'Price',
            'discount' => 'Discount',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}