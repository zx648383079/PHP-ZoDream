<?php
namespace Module\Shop\Domain\Models\Scene;

use Module\Shop\Domain\Models\CollectModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;

class Collect extends CollectModel {

    protected $append = ['goods'];

    public function goods() {
        return $this->hasOne(GoodsSimpleModel::class, 'id', 'goods_id');
    }
}