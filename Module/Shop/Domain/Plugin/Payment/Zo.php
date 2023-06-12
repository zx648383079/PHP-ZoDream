<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Plugin\Payment;

use Module\Shop\Domain\Plugin\BasePayment;
use Module\Shop\Domain\Plugin\IPaymentPlugin;

class Zo extends BasePayment implements IPaymentPlugin {
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
