<?php
namespace Domain\Model\Shopping;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;
use Zodream\Service\Factory;

/**
 * Class CartModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $goods_id
 * @property string $name
 * @property string $thumb
 * @property integer $number
 * @property float $price
 * @property integer $user_id
 */
class CartModel extends Model {
    public static function tableName() {
        return 'cart';
    }

    public static function addGoods(GoodsModel $goods, $number) {
        if ($goods->number < $number) {
            return false;
        }
        $model = static::findOne([
            'user_id' => Factory::user()->getId(),
            'goods_id' => $goods->id
        ]);
        if (empty($model)) {
            $model = new static();
            $model->goods_id = $goods->id;
            $model->name = $goods->name;
            $model->thumb = $goods->thumb;
            $model->price = $goods->price;
            $model->user_id = Factory::user()->getId();
        }
        $model->number += $number;
        $model->save();
        return $model;
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