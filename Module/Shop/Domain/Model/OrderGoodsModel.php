<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;


/**
 * Class OrderGoodsModel
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property integer $user_id
 * @property string $name
 * @property string $series_number
 * @property string $thumb
 * @property integer $number
 * @property float $price
 * @property integer $refund_id
 * @property integer $status
 * @property integer $after_sale_status
 * @property integer $comment_id
 */
class OrderGoodsModel extends Model {
    public static function tableName() {
        return 'shop_order_goods';
    }

    protected function rules() {
        return [
            'order_id' => 'required|int',
            'goods_id' => 'required|int',
            'user_id' => 'required|int',
            'name' => 'required|string:0,100',
            'series_number' => 'required|string:0,100',
            'thumb' => 'string:0,200',
            'number' => 'int',
            'price' => '',
            'refund_id' => 'int',
            'status' => 'int',
            'after_sale_status' => 'int',
            'comment_id' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'order_id' => 'Order Id',
            'goods_id' => 'Goods Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'series_number' => 'Series Number',
            'thumb' => 'Thumb',
            'number' => 'Number',
            'price' => 'Price',
            'refund_id' => 'Refund Id',
            'status' => 'Status',
            'after_sale_status' => 'After Sale Status',
            'comment_id' => 'Comment Id',
        ];
    }


    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }


    /**
     * @return float
     */
    public function getTotalAttribute() {
        return $this->price * $this->number;
    }

    public function getStatusLabelAttribute() {
        return OrderModel::$status_list[$this->status];
    }

    /**
     * 一步购买
     * @param OrderModel $order
     * @param GoodsModel $goods
     * @param integer $number
     * @return static
     */
    public static function addGoods(OrderModel $order, GoodsModel $goods, $number) {
        $model = new static();
        $model->goods_id = $goods->id;
        $model->status = $order->status;
        $model->user_id = $order->user_id;
        $model->order_id = $order->id;
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
     * @param OrderModel $order
     * @param CartModel $cart
     * @param integer $number
     * @return static
     * @throws \Exception
     */
    public static function addCartGoods(OrderModel $order, CartModel $cart, $number = null) {
        if (empty($number)) {
            $number = $cart->number;
        }
        $model = new static();
        $model->status = $order->status;
        $model->user_id = $order->user_id;
        $model->goods_id = $cart->goods_id;
        $model->order_id = $order->id;
        $model->name = $cart->goods->name;
        $model->series_number = $cart->goods->series_number;
        $model->thumb = $cart->goods->thumb;
        $model->price = $cart->price;
        $model->number = $number;
        $model->save();
        return $model;
    }
}