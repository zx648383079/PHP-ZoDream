<?php
namespace Domain\Model\Shopping;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Zodream\Service\Factory;

/**
 * Class CartModel
 * @package Domain\Model\Shopping
 * @property integer $goods_id
 * @property integer $user_id
 */
class CartModel extends BaseGoodsModel {
    public static function tableName() {
        return 'cart';
    }

    public function getTotal() {
        return bcmul($this->number, $this->price);
    }

    /**
     * 获取所有的商品
     * @return static[]
     */
    public static function getAllGoods() {
        return static::findAll(['user_id' => Factory::user()->getId()]);
    }

    /**
     * 根据ID 获取
     * @param array|string $args
     * @return static[]
     */
    public static function getSomeGoods($args) {
        return static::findAll([
            'user_id' => Factory::user()->getId(),
            'goods_id' => [
                'in',
                $args
            ]
        ]);
    }

    public function save() {
        if ($this->number <= 0) {
            return $this->delete();
        }
        return parent::save();
    }
}