<?php
namespace Module\Shop\Domain\Model;

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
 * @property integer $order_id
 * @property integer $goods_id
 * @property integer $user_id
 */
class OrderGoodsModel extends BaseGoodsModel {
    public static function tableName() {
        return 'order_goods';
    }

    /**
     * @return float
     */
    public function getTotal() {
        return bcmul($this->price, $this->number);
    }

    /**
     * 一步购买
     * @param integer $orderId
     * @param GoodsModel $goods
     * @param integer $number
     * @return static
     */
    public static function addGoods($orderId, GoodsModel $goods, $number) {
        $model = new static();
        $model->goods_id = $goods->id;
        $model->order_id = $orderId;
        $model->name = $goods->name;
        $model->thumb = $goods->thumb;
        $model->price = $goods->price;
        $model->user_id = Factory::user()->getId();
        $model->number = $number;
        $model->save();
        return $model;
    }

    /**
     * 从购物车中转入，并删除购物车中商品
     * @param integer $orderId
     * @param CartModel $cart
     * @param integer $number
     * @return static
     */
    public static function addCartGoods($orderId, CartModel $cart, $number = null) {
        if (empty($number)) {
            $number = $cart->number;
        }
        $model = new static();
        $model->goods_id = $cart->goods_id;
        $model->order_id = $orderId;
        $model->name = $cart->name;
        $model->thumb = $cart->thumb;
        $model->price = $cart->price;
        $model->user_id = Factory::user()->getId();
        $model->number = $number;
        $model->save();
        // 删除购物车中的商品
        $cart->number -= $number;
        $cart->save();
        return $model;
    }
}