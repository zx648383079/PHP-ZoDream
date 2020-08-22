<?php
namespace Module\Shop\Domain\Models\Scene;

use Module\Shop\Domain\Models\CollectModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;

/**
 * Class Collect
 * @package Module\Shop\Domain\Models\Scene
 * @property integer $id
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $created_at
 */
class Collect extends CollectModel {

    protected $append = ['goods'];

    public function goods() {
        return $this->hasOne(GoodsSimpleModel::class, 'id', 'goods_id');
    }
}