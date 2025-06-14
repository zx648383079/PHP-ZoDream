<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Cart;

use Module\SEO\Domain\Option;
use Module\Shop\Domain\Entities\GoodsEntity;
use Module\Shop\Domain\Entities\ProductEntity;
use Module\Shop\Domain\Repositories\WarehouseRepository;

class Store {

    const STATUS_NONE = 0;
    /**
     * 下单
     */
    const STATUS_ORDER = 1;
    /**
     * 支付
     */
    const STATUS_PAY = 2;
    /**
     * 发货
     */
    const STATUS_SHIPPING = 2;

    /**
     * 存储预扣库存
     * @var array
     */
    private array $data = [];
    private int $stockTime;
    private bool $useWarehouse;
    private int $orderStatus = self::STATUS_NONE;
    private int $region = 0;

    public function __construct() {
        $this->stockTime = intval(Option::value('shop_store'));
        $this->useWarehouse = Option::value('shop_warehouse', 0) > 0;
    }

    public function setStatus(int $status) {
        $this->orderStatus = $status;
        return $this;
    }

    public function setRegion(int $region) {
        $this->region = $region;
        return $this;
    }

    /**
     * 判断当前状态是否影响库存
     * @return bool
     */
    public function isImpactInventory(): bool {
        return $this->stockTime > 0 && $this->orderStatus === $this->stockTime;
    }

    /**
     * 冻结库存
     * @param ICartItem[] $goods_list
     * @return bool
     */
    public function frozen(array $goods_list): bool {
        $success = true;
        foreach ($goods_list as $item) {
            if (!$this->frozenItem($item->goodsId(), $item->productId(), $item->amount())) {
                $success = false;
                break;
            }
        }
        if (!$success) {
            $this->restore();
        }
        return $success;
    }

    /**
     * 冻结库存
     * @param int $goods_id
     * @param int $product_id
     * @param int $amount
     * @return bool
     */
    public function frozenItem(int $goods_id, int $product_id, int $amount): bool {
        if ($amount < 1 || !$this->isImpactInventory()) {
            return true;
        }
        $store = $this->get($goods_id, $product_id);
        if ($store < $amount) {
            return false;
        }
        $this->update($goods_id, $product_id, -$amount);
        $this->pushStock($goods_id, $product_id, $amount);
        return true;
    }

    /**
     * 判读库存是否充足
     * @param int $goods_id
     * @param int $product_id
     * @param int $amount
     * @return bool
     */
    public function check(int $goods_id, int $product_id, int $amount): bool {
        if ($amount < 1 || $this->stockTime < 1) {
            return true;
        }
        return $this->get($goods_id, $product_id) >= $amount;
    }

    /**
     * 获取库存
     * @param int $goods_id
     * @param int $product_id
     * @return int
     */
    public function get(int $goods_id, int $product_id): int {
        if ($this->useWarehouse && $this->region > 0) {
            return WarehouseRepository::getStock($this->region, $goods_id, $product_id);
        }
        if ($product_id > 0) {
            return intval(ProductEntity::where('id', $product_id)
                ->where('goods_id', $goods_id)->value('stock'));
        }
        return intval(GoodsEntity::query()->where('id', $goods_id)
            ->where('status', GoodsEntity::STATUS_SALE)
            ->value('stock'));
    }

    public function update(int $goods_id, int $product_id, int $amount) {
        if ($amount === 0) {
            return;
        }
        if ($this->useWarehouse && $this->region > 0) {
            WarehouseRepository::updateStock($this->region, $goods_id, $product_id, $amount);
            return;
        }
        if ($product_id > 0) {
            ProductEntity::query()->where('id', $product_id)
                ->where('goods_id', $goods_id)->updateIncrement('stock', $amount);
        } else {
            GoodsEntity::query()->where('id', $goods_id)
                ->updateIncrement('stock', $amount);
        }
    }

    /**
     * 清空，表示下单成功
     * @return bool
     */
    public function clear() {
        $this->data = [];
        return true;
    }

    /**
     * 下单失败，回滚
     * @return bool
     * @throws \Exception
     */
    public function restore() {
        foreach ($this->data as $goods_id => $items) {
            foreach ($items as $product_id => $amount) {
                $this->update($goods_id, $product_id, $amount);
                $this->data[$goods_id][$product_id] = 0;
            }
        }
        $this->data = [];
        return true;
    }

    /**
     * 手动设置冻结的库存
     * @param int $goods_id
     * @param int $product_id
     * @param int $amount
     * @return void
     */
    public function pushStock(int $goods_id, int $product_id, int $amount) {
        if (!$this->data[$goods_id]) {
            $this->data[$goods_id] = [
                $product_id => $amount
            ];
            return;
        }
        if (isset($this->data[$goods_id][$product_id])) {
            $this->data[$goods_id][$product_id] += $amount;
            return;
        }
        $this->data[$goods_id][$product_id] = $amount;
    }
}