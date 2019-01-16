<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;


/**
 * Class OrderGoodsModel
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property string $name
 * @property string $series_number
 * @property string $thumb
 * @property integer $number
 * @property float $price
 */
class OrderGoodsModel extends Model {
    public static function tableName() {
        return 'shop_order_goods';
    }

    protected function rules() {
        return [
            'order_id' => 'required|int',
            'goods_id' => 'required|int',
            'name' => 'required|string:0,100',
            'series_number' => 'required|string:0,100',
            'thumb' => 'string:0,200',
            'number' => 'int',
            'price' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'order_id' => 'Order Id',
            'goods_id' => 'Goods Id',
            'name' => 'Name',
            'series_number' => 'Series Number',
            'thumb' => 'Thumb',
            'number' => 'Number',
            'price' => 'Price',
        ];
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
        $model->series_number = $goods->series_number;
        $model->price = $goods->price;
        $model->number = $number;
        $model->save();
        return $model;
    }

    /**
     * 从购物车中转入
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
        $model->name = $cart->goods->name;
        $model->series_number = $cart->goods->series_number;
        $model->thumb = $cart->goods->thumb;
        $model->price = $cart->price;
        $model->number = $number;
        $model->save();
        return $model;
    }
}