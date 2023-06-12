<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Plugin;

use Module\Shop\Domain\Cart\ICartItem;

interface IActivityPlugin {

    public function getName(): string;

    public function getIntro(): string;

    public function isEnable(ICartItem $item): bool;

    public function calculate(array $items): array;
}
