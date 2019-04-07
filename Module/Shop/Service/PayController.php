<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PayLogModel;

class PayController extends Controller {

    public function indexAction($order, $payment) {
        $order = OrderModel::find($order);
        if ($order->status != OrderModel::STATUS_UN_PAY) {
            return;
        }
        $log = PayLogModel::create([
            'type' => PayLogModel::TYPE_ORDER,
            'payment_id' => $payment,
            'user_id' => auth()->id(),
            'data' => $order->id,
            'status' => PayLogModel::STATUS_NONE,
            'amount' => $order->order_amount,
        ]);
        dd($log);
    }

    public function notifyAction() {
        $id = intval(app('request')->get('id'));
        $log = PayLogModel::find($id);
        $log && $log->pay();
    }
}