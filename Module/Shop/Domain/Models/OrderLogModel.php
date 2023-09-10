<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

/**
 * Class OrderLogModel
 * @property integer $id
 * @property integer $order_id
 * @property integer $user_id
 * @property integer $status
 * @property string $remark
 * @property integer $created_at
 */
class OrderLogModel extends Model {
    public static function tableName(): string {
        return 'shop_order_log';
    }

    public function rules(): array {
        return [
            'order_id' => 'required|int',
            'user_id' => 'required|int',
            'status' => 'int',
            'remark' => 'string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'order_id' => 'Order Id',
            'user_id' => 'User Id',
            'status' => 'Status',
            'remark' => 'Remark',
            'created_at' => 'Created At',
        ];
    }

    public static function pay(OrderModel $order, $remark = '订单支付') {
        $order->pay_at = time();
        return static::changeStatus($order, OrderModel::STATUS_PAID_UN_SHIP, $remark);;
    }

    public static function shipping(OrderModel $order, $remark = '订单发货') {
        $order->shipping_at = time();
        return static::changeStatus($order, OrderModel::STATUS_SHIPPED, $remark);;
    }

    public static function receive(OrderModel $order, $remark = '订单签收') {
        $order->receive_at = time();
        return static::changeStatus($order, OrderModel::STATUS_RECEIVED, $remark);;
    }

    public static function finish(OrderModel $order, $remark = '订单完成') {
        $order->finish_at = time();
        return static::changeStatus($order, OrderModel::STATUS_FINISH, $remark);;
    }

    public static function cancel(OrderModel $order, $remark = '订单取消') {
        return static::changeStatus($order, OrderModel::STATUS_CANCEL, $remark);
    }

    public static function changeStatus(OrderModel $order, $status, $remark) {
        $order->status = $status;
        if (!$order->save()) {
            return false;
        }
        OrderGoodsModel::where('order_id', $order->id)->update([
            'status' => $order->status
        ]);
        static::create([
            'order_id' => $order->id,
            'user_id' => auth()->guest() ? $order->user_id : auth()->id(),
            'status' => $order->status,
            'remark' => $remark,
            'created_at' => time(),
        ]);
        return true;
    }
}