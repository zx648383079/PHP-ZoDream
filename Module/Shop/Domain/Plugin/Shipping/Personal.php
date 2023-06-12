<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Plugin\Shipping;

use Module\Shop\Domain\Plugin\BaseShipping;
use Module\Shop\Domain\Plugin\IShippingPlugin;

class Personal extends BaseShipping implements IShippingPlugin {

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