<?php
namespace Module\Shop\Domain\Plugin\Payment;

use Module\Auth\Domain\FundAccount;
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
        return '';
    }

    public function pay(array $log): array {
        $res = FundAccount::change(
            auth()->id(), FundAccount::TYPE_SHOPPING, $log['payment_id'],
            -$log['currency_money'], $log['body']);
        if (!$res) {
            PaymentRepository::payed([
                'status' => self::STATUS_FAILURE,
                'payment_id' => $log['payment_id']
            ]);
            throw new \Exception('账号余额不足');
        }
        PaymentRepository::payed([
            'status' => self::STATUS_SUCCESS,
            'payment_id' => $log['payment_id'],
            'trade_no' => $res
        ]);
        return $this->toUrl($log['return_url']);
    }

    public function callback(array $input): array {
        return [];
    }

    public function refund(array $log): array {
        return [];
    }


}
