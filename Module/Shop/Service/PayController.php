<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PayLogModel;
use Module\Shop\Domain\Models\PaymentModel;
use Zodream\Helpers\Str;

class PayController extends Controller {

    public function indexAction($order, $payment) {
        $order = OrderModel::find($order);
        if ($order->status != OrderModel::STATUS_UN_PAY) {
            return;
        }
        $payment = PaymentModel::find($payment);
        $log = PayLogModel::create([
            'type' => PayLogModel::TYPE_ORDER,
            'payment_id' => $payment->id,
            'user_id' => auth()->id(),
            'data' => $order->id,
            'status' => PayLogModel::STATUS_NONE,
            'amount' => $order->order_amount,
        ]);
        $notify_url = url('./pay/notify/0/code/'.Str::unStudly($payment->code));

    }

    public function notifyAction($code) {
        $id = intval(app('request')->get('id'));
        $log = PayLogModel::find($id);
        $log && $log->pay();
    }
}