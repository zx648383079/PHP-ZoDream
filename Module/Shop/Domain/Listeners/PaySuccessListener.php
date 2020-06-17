<?php
namespace Module\Shop\Domain\Listeners;


use Module\Shop\Domain\Events\PaySuccess;
use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PayLogModel;

/**
 * 支付成功监听
 * @package Module\Shop\Domain\Listeners
 */
class PaySuccessListener {

    public function __construct(PaySuccess $pay) {
        $type = $pay->getType();
        if ($type === PayLogModel::TYPE_ORDER) {
            return $this->payOrder($pay->getLog());
        }
        if ($type === PayLogModel::TYPE_BALANCE) {
            return $this->payBalance();
        }

    }

    public function payOrder(PayLogModel $log) {
        $order_list = OrderModel::whereIn('id', explode(',', $log->data))
            ->get();
        foreach ($order_list as $order) {
            if ($order->status != OrderModel::STATUS_UN_PAY) {
                continue;
            }
            OrderLogModel::pay($order);
        }
    }

    public function payBalance() {

    }
}