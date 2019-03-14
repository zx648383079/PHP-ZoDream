<?php
namespace Module\Shop\Domain\Model\Scene;

use Module\Shop\Domain\Model\CollectModel;

class Collect extends CollectModel {

    protected $append = ['goods'];

    public function goods() {
        return $this->hasOne(Goods::class, 'id', 'goods_id');
    }
}