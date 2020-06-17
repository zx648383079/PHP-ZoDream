<?php
namespace Module\Legwork\Domain\Listeners;


use Module\Legwork\Domain\Model\OrderLogModel;
use Module\Legwork\Domain\Model\OrderModel;
use Module\Shop\Domain\Events\PaySuccess;
use Module\Shop\Domain\Models\PayLogModel;

/**
 * 支付成功监听
 * @package Module\Legwork\Domain\Listeners
 */
class PaySuccessListener {

    public function __construct(PaySuccess $pay) {
        $type = $pay->getType();
        if ($type === 27) {
            return $this->payOrder($pay->getLog());
        }

    }

    public function payOrder(PayLogModel $log) {
        $order = OrderModel::find($log->data);
        if (empty($order)) {
            return;
        }
        OrderLogModel::pay($order);
    }
}