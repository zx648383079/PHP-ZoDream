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
 * Class OrderGoodsModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property string $name
 * @property string $thumb
 * @property integer $number
 * @property float $price
 * @property integer $user_id
 */
class OrderGoodsModel extends Model {
    public static function tableName() {
        return 'order_goods';
    }

    /**
     * @return float
     */
    public function getTotal() {
        return bcmul($this->price, $this->number);
    }


}