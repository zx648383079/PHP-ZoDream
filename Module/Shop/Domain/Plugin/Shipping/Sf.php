<?php
namespace Module\Shop\Domain\Plugin\Shipping;

use Module\Shop\Domain\Plugin\BaseShipping;

class Sf extends BaseShipping {

    public function getName(): string {
        return '顺丰速递';
    }

    public function getIntro(): string {
        return '';
    }

    public function calculate(int $amount, float $price, float $weight): float {
        return 0;
    }
}