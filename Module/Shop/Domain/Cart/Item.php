<?php
namespace Module\Shop\Domain\Cart;

trait Item  {

    public function getGroupName() {
        return '自营';
    }

    public function getId() {
        return $this->id;
    }

    public function canMerge(ICartItem $item) {
        return $this->productId() == $item->productId() && $this->goodsId() == $item->goodsId();
    }

    public function mergeItem(ICartItem $item) {
        $this->amount += $item->amount();
        return $this;
    }

    public function goodsId() {
        return $this->goods_id;
    }

    public function productId() {
        return 0;
    }

    public function total() {
        return $this->price * $this->amount();
    }

    public function amount() {
        return $this->amount;
    }
}