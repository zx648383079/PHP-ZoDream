<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 订单
 * @property integer $id
 * @property integer $user_id
 * @property integer $store_id
 * @property integer $address_type
 * @property string $address_name
 * @property string $address_tel
 * @property string $address
 * @property integer $payment_id
 * @property string $payment_name
 * @property integer $shipping_id
 * @property string $shipping_name
 * @property float $goods_amount
 * @property float $order_amount
 * @property float $discount
 * @property float $shipping_fee
 * @property float $pay_fee
 * @property integer $status
 * @property string $remark
 * @property string $reserve_at
 * @property integer $pay_at
 * @property integer $shipping_at
 * @property integer $receive_at
 * @property integer $finish_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class OrderEntity extends Entity {
    public static function tableName(): string {
        return 'eat_order';
    }


    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'store_id' => 'required|int',
            'address_type' => 'int:0,127',
            'address_name' => 'string:0,20',
            'address_tel' => 'string:0,20',
            'address' => 'string:0,255',
            'payment_id' => 'int',
            'payment_name' => 'string:0,30',
            'shipping_id' => 'int',
            'shipping_name' => 'string:0,30',
            'goods_amount' => 'string',
            'order_amount' => 'string',
            'discount' => 'string',
            'shipping_fee' => 'string',
            'pay_fee' => 'string',
            'status' => 'int:0,127',
            'remark' => 'string:0,255',
            'reserve_at' => 'string:0,255',
            'pay_at' => 'int',
            'shipping_at' => 'int',
            'receive_at' => 'int',
            'finish_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'store_id' => 'Store Id',
            'address_type' => 'Address Type',
            'address_name' => 'Address Name',
            'address_tel' => 'Address Tel',
            'address' => 'Address',
            'payment_id' => 'Payment Id',
            'payment_name' => 'Payment Name',
            'shipping_id' => 'Shipping Id',
            'shipping_name' => 'Shipping Name',
            'goods_amount' => 'Goods Amount',
            'order_amount' => 'Order Amount',
            'discount' => 'Discount',
            'shipping_fee' => 'Shipping Fee',
            'pay_fee' => 'Pay Fee',
            'status' => 'Status',
            'remark' => 'Remark',
            'reserve_at' => 'Reserve At',
            'pay_at' => 'Pay At',
            'shipping_at' => 'Shipping At',
            'receive_at' => 'Receive At',
            'finish_at' => 'Finish At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}