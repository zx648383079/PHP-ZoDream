<?php
namespace Module\Shop\Domain\Model\Scene;

use Module\Shop\Domain\Model\OrderModel;

class Order extends OrderModel {

    protected $append = ['goods'];

    public function goods() {
        return $this->hasOne(Goods::class, 'id', 'goods_id');
    }
}