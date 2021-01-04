<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Events\PaySuccess;
use Module\Shop\Domain\Listeners\PaySuccessListener;
use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PayLogModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Plugin\BasePayment;
use Module\Shop\Domain\Plugin\Manager;
use Module\Shop\Module;
use Zodream\Helpers\Json;
use Zodream\Route\Router;

class PaymentRepository {

    public static function payOrder(OrderModel $order, PaymentModel $payment) {
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
        return self::pay($log, $payment, [
            'order_id' => $order->id,
            'body' => '订单支付',
        ]);
    }

    public static function pay(PayLogModel $log, PaymentModel $payment, array $extra = []) {
        // 如果报错，则表示当前模块未安装
        list($notify_url, $return_url) = app(Router::class)->module(Module::class,
            function () use ($payment, $log) {
           return [
               url(sprintf('./pay/notify/0/platform/%d/payment/%d',
                   app()->has('platform') ? app('platform')->id() : 0,
                   $payment->id)),
               url('./pay/result/id/'. $log->id)
           ];
        });
        $data = array_merge([
            'order_id' => 0,
            'body' => '订单支付'
        ], $extra, [
            'payment_id' => $log->id,
            'user_id' => $log->user_id,
            'currency' => $log->currency,
            'currency_money' => $log->currency_money,
            'return_url' => $return_url,
            'notify_url' => $notify_url,
            'ip' => request()->ip()
        ]);
        if ($data['currency_money'] <= 0) {
            throw new \Exception('订单金额有误');
        }
        return self::getPayee($payment)->pay($data);
    }

    /**
     * @param PaymentModel $payment
     * @return string|mixed
     * @throws \Exception
     */
    public static function callback(PaymentModel $payment) {
        $res = self::getPayee($payment)
            ->callback(request()->get());
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
        if (isset($res['money']) && $res['money'] != $log->currency_money) {
            // 金额不对
        }
        $log->confirm_at = isset($res['payed_at']) ? $res['payed_at'] : time();
        $log->status = $res['status'] === 'SUCCESS'
            ? PayLogModel::STATUS_SUCCESS : PayLogModel::STATUS_FAILURE;
        if (!$log->save() || $res['status'] !== 'SUCCESS') {
            return false;
        }
        try {
            $event = new PaySuccess($log);
            new PaySuccessListener($event);
            event($event);
        } catch (\Exception $ex) {
            return false;
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
     * 获取支付成功的记录
     * @param OrderModel $order
     * @return PayLogModel|bool
     */
    public static function getPayedLog(OrderModel $order) {
        return PayLogModel::query()->where('type', PayLogModel::TYPE_ORDER)
            ->where('payment_id', $order->payment_id)
            ->where('status', PayLogModel::STATUS_SUCCESS)->where('data', $order->id)
            ->first();
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