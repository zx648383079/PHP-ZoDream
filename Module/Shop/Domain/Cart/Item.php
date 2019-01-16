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
        return $this->goods_id == $item->goods_id;
    }

    public function mergeItem(ICartItem $item) {
        $this->number += $item->amount();
        return $this;
    }

    public function total() {
        return $this->price * $this->amount();
    }

    public function amount() {
        return $this->number;
    }
}