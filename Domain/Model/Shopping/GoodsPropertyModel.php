<?php
namespace Domain\Model\Shopping;

use Domain\Model\Model;

/**
 * Class GoodsPropertyModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $goods_id
 * @property string $name
 * @property integer $value
 */
class GoodsPropertyModel extends Model {
    public static function tableName() {
        return 'goods_property';
    }
}