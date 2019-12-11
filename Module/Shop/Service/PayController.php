<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PayLogModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Repositories\PaymentRepository;
use Zodream\Helpers\Str;

class PayController extends Controller {

    public function indexAction($order, $payment) {
        $order = OrderModel::find($order);
        if ($order->status != OrderModel::STATUS_UN_PAY) {
            return;
        }
        $payment = PaymentModel::find($payment);
        PaymentRepository::pay($order, $payment);
    }

    public function notifyAction($payment) {
        PaymentRepository::callback(PaymentModel::find($payment));
    }
}