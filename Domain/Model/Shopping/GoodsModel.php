<?php
namespace Domain\Model\Shopping;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

/**
 * Class GoodsModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $name
 * @property string $thumb
 * @property integer $number
 * @property float $price
 * @property integer $create_at
 * @property integer $update_at
 */
class GoodsModel extends Model {
    public static function tableName() {
        return 'goods';
    }
}