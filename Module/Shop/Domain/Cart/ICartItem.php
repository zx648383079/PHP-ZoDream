<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Cart;

interface ICartItem {

    public function getGroupName(): string;

    public function getId(): int|string;

    public function canMerge(ICartItem $item);

    public function mergeItem(ICartItem $item);

    public function goodsId(): int|string;

    public function productId(): int|string;

    public function total(): int|float;

    public function amount(): int;

    public function updateAmount(int $amount);

    public function save();

    public function invalid(): bool;
}