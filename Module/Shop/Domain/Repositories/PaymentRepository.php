<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PayLogModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Plugin\Manager;

class PaymentRepository {

    public static function pay(OrderModel $order, PaymentModel $payment) {
        $log = PayLogModel::create([
            'type' => PayLogModel::TYPE_ORDER,
            'payment_id' => $payment->id,
            'user_id' => auth()->id(),
            'data' => $order->id,
            'status' => PayLogModel::STATUS_NONE,
            'amount' => $order->order_amount,
        ]);
        $notify_url = url('./pay/notify/payment/'.$payment->id);
        Manager::payment($payment->code)->pay([

        ]);
    }

    public static function callback(PaymentModel $payment) {
        $res = Manager::payment($payment->code)->callback(app('request')->get());

        $log = PayLogModel::find($res['log_id']);
        $log && $log->pay();
    }

    public static function getPlugins() {
        $items = [];
        $data = Manager::all('payment');
        foreach ($data as $item) {
            $items[$item] = Manager::payment($item)->getName();
        }
        return $items;
    }
}