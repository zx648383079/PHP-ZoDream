<?php
namespace Module\Shop\Domain\Models\Scene;

use Module\Shop\Domain\Models\CollectModel;

class Collect extends CollectModel {

    protected $append = ['goods'];

    public function goods() {
        return $this->hasOne(Goods::class, 'id', 'goods_id');
    }
}