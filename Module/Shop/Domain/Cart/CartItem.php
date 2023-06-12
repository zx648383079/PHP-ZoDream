<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Cart;

use Module\Shop\Domain\Repositories\CashierRepository;

class CartItem implements ICartItem {
    public function __construct(
        protected array $data) {
        $this->isUpdated = empty($this->getId());
    }



    protected bool $isUpdated = false;

    public function getData(): array {
        return $this->data;
    }
    public function setData(array $data) {
        $this->data = array_merge($this->data, $data);
    }
    public function isUpdated(): bool {
        return $this->isUpdated;
    }

    public function getGroupName(): string {
        return isset($this->data['selected_activity']) && $this->data['selected_activity'] > 0 ?
            sprintf('联合活动[#%d]', $this->data['selected_activity']) :  '自营';
    }

    public function getId(): int|string {
        return $this->data['id'] ?? 0;
    }

    public function canMerge(ICartItem $item): bool {
        return $this->productId() === $item->productId()
            && $this->goodsId() === $item->goodsId() && $this->properties() === $item->properties();
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
        $this->data['amount'] += $item->amount();
        $this->isUpdated = true;
        return $this;
    }

    public function goodsId(): int|string {
        return $this->data['goods_id'];
    }

    public function productId(): int|string {
        return $this->data['product_id'];
    }

    public function activityId(): int|string|null {
        return intval($this->data['selected_activity']);
    }

    public function properties(): array {
        if (is_array($this->data['attribute_id'])) {
            return $this->data['attribute_id'];
        }
        return empty($this->data['attribute_id']) ? [] : explode(',', $this->data['attribute_id']);
    }

    public function total(): int|float {
        return $this->price() * $this->amount();
    }

    public function price(): int|float {
        return floatval($this->data['price']);
    }

    public function amount(): int {
        return $this->data['amount'];
    }

    public function invalid(): bool {
        return !CashierRepository::store()->check($this->goodsId(), $this->productId(), $this->amount());
    }

    public function updateAmount(int $amount) {
        $this->data['amount'] = $amount;
        $this->isUpdated = true;
        return $this;
    }

    public function updatePrice(float $price, int $activity = 0) {
        $this->data['price'] = $price;
        $this->data['selected_activity'] = $activity;
        $this->isUpdated = true;
        return $this;
    }

}