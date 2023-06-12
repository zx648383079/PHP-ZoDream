<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Plugin;

interface IPaymentPlugin {
    public function getName(): string;

    public function getIntro(): string;

    public function settings(): array;

    public function preview(): string;

    public function pay(array $log): array;

    public function callback(array $input): array;

    public function refund(array $log): array;

}
