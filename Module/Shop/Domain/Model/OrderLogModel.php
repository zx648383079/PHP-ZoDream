<?php
namespace Module\Shop\Domain\Model;

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
    public static function tableName() {
        return 'shop_order_log';
    }

    protected function rules() {
        return [
            'order_id' => 'required|int',
            'user_id' => 'required|int',
            'status' => 'int',
            'remark' => 'string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'order_id' => 'Order Id',
            'user_id' => 'User Id',
            'status' => 'Status',
            'remark' => 'Remark',
            'created_at' => 'Created At',
        ];
    }

    public static function pay(OrderModel $order) {
        $order->status = OrderModel::STATUS_PAID_UN_SHIP;
        $order->pay_at = time();
        if (!$order->save()) {
            return false;
        }
        OrderGoodsModel::where('order_id', $order->id)->update([
            'status' => $order->status
        ]);
        static::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'status' => $order->status,
            'remark' => '订单支付',
            'created_at' => time(),
        ]);
        return true;
    }

    public static function shipping(OrderModel $order) {
        $order->status = OrderModel::STATUS_SHIPPED;
        $order->shipping_at = time();
        if (!$order->save()) {
            return false;
        }
        OrderGoodsModel::where('order_id', $order->id)->update([
            'status' => $order->status
        ]);
        static::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'status' => $order->status,
            'remark' => '订单发货',
            'created_at' => time(),
        ]);
        return true;
    }

    public static function receive(OrderModel $order) {
        $order->status = OrderModel::STATUS_RECEIVED;
        $order->receive_at = time();
        if (!$order->save()) {
            return false;
        }
        OrderGoodsModel::where('order_id', $order->id)->update([
            'status' => $order->status
        ]);
        static::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'status' => $order->status,
            'remark' => '订单签收',
            'created_at' => time(),
        ]);
        return true;
    }

    public static function finish(OrderModel $order) {
        $order->status = OrderModel::STATUS_FINISH;
        $order->finish_at = time();
        if (!$order->save()) {
            return false;
        }
        OrderGoodsModel::where('order_id', $order->id)->update([
            'status' => $order->status
        ]);
        static::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'status' => $order->status,
            'remark' => '订单完成',
            'created_at' => time(),
        ]);
        return true;
    }

    public static function cancel(OrderModel $order) {
        $order->status = OrderModel::STATUS_CANCEL;
        if (!$order->save()) {
            return false;
        }
        OrderGoodsModel::where('order_id', $order->id)->update([
            'status' => $order->status
        ]);
        static::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'status' => $order->status,
            'remark' => '订单取消',
            'created_at' => time(),
        ]);
        return true;
    }
}