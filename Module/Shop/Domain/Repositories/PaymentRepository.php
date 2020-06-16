<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PayLogModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Plugin\BasePayment;
use Module\Shop\Domain\Plugin\Manager;
use Zodream\Helpers\Json;

class PaymentRepository {

    public static function pay(OrderModel $order, PaymentModel $payment) {
        $log = PayLogModel::create([
            'type' => PayLogModel::TYPE_ORDER,
            'payment_id' => $payment->id,
            'user_id' => auth()->id(),
            'data' => $order->id,
            'status' => PayLogModel::STATUS_NONE,
            'amount' => $order->order_amount,
            'currency' => 'CNY',
            'payment_name' => $payment->name,
            'currency_money' => $order->order_amount,
            'begin_at' => time(),
        ]);
        $notify_url = url(sprintf('./pay/notify/0/platform/%d/payment/%d',
            app()->has('platform') ? app('platform')->id() : 0,
            $payment->id));
        $return_url = url('./pay/result/id/'. $log->id);
        return self::getPayee($payment)->pay([
            'payment_id' => $log->id,
            'order_id' => $order->id,
            'user_id' => $log->user_id,
            'currency' => $log->currency,
            'currency_money' => $log->currency_money,
            'body' => '订单支付',
            'return_url' => $return_url,
            'notify_url' => $notify_url,
            'ip' => app('request')->ip()
        ]);
    }

    /**
     * @param PaymentModel $payment
     * @return string|mixed
     * @throws \Exception
     */
    public static function callback(PaymentModel $payment) {
        $res = self::getPayee($payment)
            ->callback(app('request')->get());
        $res['output'] = isset($res['output']) ? $res['output'] : '';
        if ($res['status'] !== 'SUCCESS') {
            return $res['output'];
        }
        self::payed($res);
        return $res['output'];
    }

    public static function payed(array $res) {
        $log = PayLogModel::find($res['payment_id']);
        if (!$log) {
            return false;
        }
        if (isset($res['trade_no'])) {
            $log->trade_no = $res['trade_no'];
        }
        $log->confirm_at = isset($res['payed_at']) ? $res['payed_at'] : time();
        $log->status = $res['status'] === 'SUCCESS'
            ? PayLogModel::STATUS_SUCCESS : PayLogModel::STATUS_FAILURE;
        if (!$log->save() || $res['status'] !== 'SUCCESS') {
            return false;
        }
        if ($log->type != PayLogModel::TYPE_ORDER) {
            return true;
        }
        $order_list = OrderModel::whereIn('id', explode(',', $log->data))
            ->get();
        foreach ($order_list as $order) {
            if ($order->status != OrderModel::STATUS_UN_PAY) {
                continue;
            }
            OrderLogModel::pay($order);
        }
        return true;
    }

    /**
     * @param PaymentModel $payment
     * @return BasePayment
     */
    public static function getPayee(PaymentModel $payment) {
        return Manager::payment($payment->code)
            ->setConfigs(empty($payment->settings) ? [] : Json::decode($payment->settings));
    }

    public static function getPlugins() {
        $items = [];
        $data = Manager::all('payment');
        foreach ($data as $item) {
            $items[$item] = Manager::payment($item)->getName();
        }
        return $items;
    }

    public static function refund(OrderModel $order, $type, $money = 0) {
        if ($order->status < OrderModel::STATUS_PAID_UN_SHIP
        || $order->status > OrderModel::STATUS_FINISH) {
            return false;
        }
        $money = $money <= 0 ? $order->order_amount : $money;
        if ($money > $order->order_amount) {
            return false;
        }
        if ($type == 1) {

        }
    }


    /**
     * 支付费用
     * @param PaymentModel $payment
     * @param array $goods_list
     * @return int
     */
    public static function getFee(PaymentModel $payment, array $goods_list) {
        return 0;
    }
}