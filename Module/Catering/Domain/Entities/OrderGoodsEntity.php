<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 订单
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property integer $amount
 * @property float $price
 * @property float $discount
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class OrderGoodsEntity extends Entity {
    public static function tableName() {
        return 'eat_order_goods';
    }

    protected function rules() {
        return [
            'order_id' => 'required|int',
            'goods_id' => 'required|int',
            'amount' => 'required|int',
            'price' => 'required|string',
            'discount' => 'required|string',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'order_id' => 'Order Id',
            'goods_id' => 'Goods Id',
            'amount' => 'Amount',
            'price' => 'Price',
            'discount' => 'Discount',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}