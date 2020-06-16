<?php
namespace Module\Shop\Domain\Plugin;

abstract class BaseShipping {

    abstract public function getName(): string;

    abstract public function getIntro(): string;

    abstract public function calculate(array $settings, int $amount, float $price, float $weight): float;
}
