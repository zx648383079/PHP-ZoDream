<?php
namespace Module\Shop\Domain\Plugin\Payment;

use Module\Shop\Domain\Plugin\BasePayment;

class Zo extends BasePayment {
    public function getName(): string {
        return 'Zo支付';
    }

    public function getIntro(): string {
        // TODO: Implement getIntro() method.
    }

    public function preview(): string {
        // TODO: Implement preview() method.
    }

    public function pay(array $log): array {
        // TODO: Implement pay() method.
    }

    public function callback(array $input): array {
        // TODO: Implement callback() method.
    }

    public function refund(array $log): array {
        // TODO: Implement refund() method.
    }
}
