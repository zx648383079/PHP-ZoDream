<?php
namespace Module\Shop\Domain\Cart;

use Module\Shop\Domain\Models\GoodsModel;

class Store {

    /**
     * 存储预扣库存
     * @var array
     */
    private $data = [];

    /**
     * 冻结库存
     * @param ICartItem[] $goods_list
     * @return bool
     */
    public function frozen(array $goods_list): bool {
        $success = true;
        foreach ($goods_list as $item) {
            if (!$this->frozenGoods($item->goodsId(), $item->productId(), $item->amount())) {
                $success = false;
                break;
            }
        }
        if (!$success) {
            $this->restore();
        }
        return $success;
    }

    public function frozenGoods($goods_id, $product_id, $amount) {
        $store = GoodsModel::query()->where('goods_id', $goods_id)->value('stock');
        if ($store < $amount) {
            return false;
        }
        GoodsModel::query()->where('goods_id', $goods_id)
            ->updateOne('store', -$amount);
        $this->data[$goods_id][$product_id] = $amount;
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
            foreach ($items as $amount) {
                GoodsModel::query()->where('goods_id', $goods_id)
                    ->updateOne('store', $amount);
            }
        }
        $this->data = [];
        return true;
    }
}