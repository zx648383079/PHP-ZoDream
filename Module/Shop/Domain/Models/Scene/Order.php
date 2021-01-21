<?php
namespace Module\Shop\Domain\Models\Scene;

use Module\Shop\Domain\Models\OrderModel;

/**
 * Class Order
 * @package Module\Shop\Domain\Models\Scene
 * @property integer $id
 * @property string $series_number
 * @property integer $user_id
 * @property integer $status
 * @property integer $payment_id
 * @property string $payment_name
 * @property integer $shipping_id
 * @property string $shipping_name
 * @property float $goods_amount
 * @property float $order_amount
 * @property float $discount
 * @property float $shipping_fee
 * @property float $pay_fee
 * @property integer $reference
 * @property integer $pay_at
 * @property integer $shipping_at
 * @property integer $receive_at
 * @property integer $finish_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class Order extends OrderModel {

    protected array $append = ['goods', 'status_label'];
}