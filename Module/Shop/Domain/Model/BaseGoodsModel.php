<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

/**
 * Class BaseGoodsModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $name
 * @property string $thumb
 * @property integer $number
 * @property float $price
 * @property float $market_price
 * @property integer $type
 */
abstract class BaseGoodsModel extends Model {
    const TYPE_REAL = 0;    //真实
    const TYPE_VIRTUAL = 1; //虚拟

    public function getCategory() {
        return $this->hasOne(CategoryModel::class, 'id', 'category_id');
    }
}