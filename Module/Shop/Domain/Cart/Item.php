<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Cart;

use Module\Shop\Domain\Repositories\CashierRepository;

trait Item  {

    protected bool $isUpdated = false;

    public function getData(): array {
        return $this->getAttribute();
    }
    public function setData(array $data) {
        $this->setAttribute($data);
    }

    public function isUpdated(): bool {
        return $this->isUpdated;
    }

    public function getGroupName(): string {
        return $this->selected_activity > 0 ? sprintf('联合活动[#%d]', $this->selected_activity) :  '自营';
    }

    public function getId(): int|string {
        return $this->id;
    }

    public function canMerge(ICartItem $item): bool {
        return $this->productId() == $item->productId() && $this->goodsId() == $item->goodsId();
    }

    public function is(int|string $goodsId, array|string $properties = ''): bool {
        if ($this->goodsId() !== $goodsId) {
            return false;
        }
        if (is_array($properties)) {
            return implode(',', $properties) === $this->properties();
        }
        return $properties === $this->properties();
    }

    public function mergeItem(ICartItem $item) {
        $this->amount += $item->amount();
        return $this;
    }

    public function goodsId(): int|string {
        return $this->goods_id;
    }

    public function productId(): int|string {
        return $this->product_id;
    }

    public function properties(): string {
        return $this->attribute_id;
    }

    public function total(): int|float {
        return $this->price * $this->amount();
    }

    public function amount(): int {
        return $this->amount;
    }

    public function invalid(): bool {
        return !CashierRepository::store()->check($this->goodsId(), $this->productId(), $this->amount());
    }
}