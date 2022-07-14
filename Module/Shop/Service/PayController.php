<?php
declare(strict_types=1);
namespace Module\Shop\Service;

use Module\OpenPlatform\Domain\Platform;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PayLogModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Repositories\PaymentRepository;

class PayController extends Controller {

    protected function allowAccess(): bool {
        return true;
    }

    public function indexAction(int $order, int $payment) {
        $order = OrderModel::find($order);
        if ($order->status != OrderModel::STATUS_UN_PAY) {
            return;
        }
        $payment = PaymentModel::find($payment);
        try {
            $data = PaymentRepository::payOrder($order, $payment);
        } catch (\Exception $ex) {
            if (request()->isAjax()) {
                return $this->renderFailure($ex->getMessage());
            }
            return $ex->getMessage();
        }
        if (request()->isAjax()) {
            return $this->renderData($data);
        }
        if (isset($data['url'])) {
            return $this->redirect($data['url']);
        }
        return $this->sendWithShare()->show($data);
    }

    public function notifyAction(int $payment, int $platform = 0) {
        try {
            if ($platform > 0) {
                Platform::enterPlatform($platform);
            }
            return PaymentRepository::callback(PaymentModel::find($payment));
        } catch (\Exception $ex) {
            logger(sprintf('(%s):%s|>>%s', url()->full(),
                request()->input(),
                $ex->getMessage()));
            return 'failure';
        }
    }

    public function resultAction(int $id) {
        $log = PayLogModel::find($id);
        return $this->sendWithShare()->show(compact('log'));
    }
}