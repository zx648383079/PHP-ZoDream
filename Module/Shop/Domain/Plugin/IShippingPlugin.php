<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Plugin;

interface IShippingPlugin {
    public function getName(): string;

    public function getIntro(): string;

    public function calculate(array $settings, int $amount, float $price, float $weight): float;
}
