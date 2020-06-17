<?php
namespace Module\Shop\Service;

use Module\OpenPlatform\Domain\Platform;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PayLogModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Repositories\PaymentRepository;

class PayController extends Controller {

    protected $disallow = false;

    public function indexAction($order, $payment) {
        $order = OrderModel::find($order);
        if ($order->status != OrderModel::STATUS_UN_PAY) {
            return;
        }
        $payment = PaymentModel::find($payment);
        try {
            $data = PaymentRepository::payOrder($order, $payment);
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

    public function notifyAction($payment, $platform = 0) {
        try {
            if ($platform > 0) {
                Platform::enterPlatform(intval($platform));
            }
            return PaymentRepository::callback(PaymentModel::find($payment));
        } catch (\Exception $ex) {
            logger(sprintf('(%s):%s|>>%s', url()->full(),
                app('request')->input(),
                $ex->getMessage()));
            return 'failure';
        }
    }

    public function resultAction($id) {
        $log = PayLogModel::find($id);
        return $this->sendWithShare()->show(compact('log'));
    }
}