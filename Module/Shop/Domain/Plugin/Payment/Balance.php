<?php
namespace Module\Shop\Domain\Plugin\Payment;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Shop\Domain\Plugin\BasePayment;
use Module\Shop\Domain\Repositories\PaymentRepository;

class Balance extends BasePayment {

    public function getName(): string {
        return '余额支付';
    }

    public function getIntro(): string {
        return '余额支付';
    }

    public function preview(): string {
        // TODO: Implement preview() method.
    }

    public function pay(array $log): array {
        $res = AccountLogModel::change(
            auth()->id(), AccountLogModel::TYPE_SHOPPING, $log['payment_id'],
            $log['currency_money'], $log['body']);
        if (!$res) {
            PaymentRepository::payed([
                'status' => 'FAILURE',
                'payment_id' => $log['payment_id']
            ]);
            return $this->toUrl($log['return_url']);
        }
        PaymentRepository::payed([
            'status' => 'SUCCESS',
            'payment_id' => $log['payment_id'],
            'trade_no' => $res
        ]);
        return $this->toUrl($log['return_url']);
    }

    public function callback(array $input): array {
        // TODO: Implement callback() method.
    }

    public function refund(array $log): array {
        // TODO: Implement refund() method.
    }


}
