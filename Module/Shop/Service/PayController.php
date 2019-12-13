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
        try {
            $data = PaymentRepository::pay($order, $payment);
        } catch (\Exception $ex) {
            if (app('request')->isAjax()) {
                return $this->jsonFailure($ex->getMessage());
            }
            return $ex->getMessage();
        }
        if (app('request')->isAjax()) {
            return $this->jsonSuccess($data);
        }
        if (isset($data['url'])) {
            return $this->redirect($data['url']);
        }
        return $this->sendWithShare()->show($data);
    }

    public function notifyAction($payment) {
        return PaymentRepository::callback(PaymentModel::find($payment));
    }

    public function resultAction($id) {
        $log = PayLogModel::find($id);
        return $this->sendWithShare()->show(compact('log'));
    }
}