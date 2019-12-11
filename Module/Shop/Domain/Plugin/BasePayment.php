<?php
namespace Module\Shop\Domain\Plugin;

abstract class BasePayment {

    abstract public function getName(): string;

    abstract public function getIntro(): string;

    abstract public function preview(): string;

    abstract public function pay(): array;

    abstract public function callback(): array;

    abstract public function refund(): array;


}
