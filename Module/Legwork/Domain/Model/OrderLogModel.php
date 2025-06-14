<?php
namespace Module\Legwork\Domain\Model;

use Domain\Model\Model;

/**
 * Class OrderLogModel
 * @package Module\Legwork\Domain\Model
 * @property integer $id
 * @property integer $order_id
 * @property integer $user_id
 * @property integer $status
 * @property string $remark
 * @property integer $created_at
 */
class OrderLogModel extends Model {
    public static function tableName(): string {
        return 'leg_order_log';
    }

    protected function rules(): array {
        return [
            'order_id' => 'required|int',
            'user_id' => 'required|int',
            'status' => 'int:0,127',
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
        return static::changeStatus($order, OrderModel::STATUS_PAID_UN_TAKING, $remark);;
    }

    public static function taking(OrderModel $order, $remark = '订单已接单') {
        $order->taking_at = time();
        return static::changeStatus($order, OrderModel::STATUS_TAKING_UN_DO, $remark);;
    }

    public static function taken(OrderModel $order, $remark = '订单执行完成') {
        $order->taken_at = time();
        return static::changeStatus($order, OrderModel::STATUS_TAKEN, $remark);;
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