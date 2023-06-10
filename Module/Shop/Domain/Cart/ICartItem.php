<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Cart;

interface ICartItem {

    public function getData(): array;
    public function setData(array $data);
    /**
     * 是否需要更新
     * @return bool
     */
    public function isUpdated(): bool;

    public function getGroupName(): string;

    public function getId(): int|string;

    public function canMerge(ICartItem $item): bool;

    public function is(int|string $goodsId, array|string $properties = ''): bool;

    public function mergeItem(ICartItem $item);

    public function goodsId(): int|string;

    public function productId(): int|string;

    public function properties(): string;

    public function total(): int|float;

    public function amount(): int;

    public function updateAmount(int $amount);

    public function invalid(): bool;
}