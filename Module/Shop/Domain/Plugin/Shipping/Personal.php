<?php
namespace Module\Shop\Domain\Plugin\Shipping;

use Module\Shop\Domain\Plugin\BaseShipping;

class Personal extends BaseShipping {

    public function getName(): string {
        return '商家配送';
    }

    public function getIntro(): string {
        return '';
    }

    public function calculate(array $settings, int $amount, float $price, float $weight): float {
        return 0;
    }
}