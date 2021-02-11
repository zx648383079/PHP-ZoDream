<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Cart;

use Module\Shop\Domain\Models\GoodsModel;

trait Item  {

    public function getGroupName(): string {
        return '自营';
    }

    public function getId(): int|string {
        return $this->id;
    }

    public function canMerge(ICartItem $item) {
        return $this->productId() == $item->productId() && $this->goodsId() == $item->goodsId();
    }

    public function mergeItem(ICartItem $item) {
        $this->amount += $item->amount();
        return $this;
    }

    public function goodsId(): int|string {
        return $this->goods_id;
    }

    public function productId(): int|string {
        return 0;
    }

    public function total(): int|float {
        return $this->price * $this->amount();
    }

    public function amount(): int {
        return $this->amount;
    }

    public function invalid(): bool {
        $store = GoodsModel::query()->where('id', $this->goodsId())
            ->where('status', GoodsModel::STATUS_SALE)
            ->value('stock');
        return empty($store) || $store < $this->amount();
    }
}