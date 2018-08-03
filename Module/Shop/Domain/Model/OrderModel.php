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
 * Class OrderModel
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property integer $payment_id
 * @property integer $shipping_id
 * @property float $goods_amount
 * @property float $order_amount
 * @property float $discount
 * @property float $shipping_fee
 * @property float $pay_fee
 * @property integer $created_at
 * @property integer $updated_at
 */
class OrderModel extends Model {

    const ORDER_UNCONFIRMED = 0;
    const ORDER_CONFIRMED = 1;
    const ORDER_CANCELED = 2;
    const ORDER_INVALID = 3;
    const ORDER_RETURNED = 4;

    const SHIPPING_UNSHIPPED = 0; //未发货
    const SHIPPING_SHIPPED = 1;  //已发货
    const SHIPPING_RECEIVED = 2; //已签收
    const SHIPPING_PREPARING = 3; //备货中

    const PAY_UNPAYED = 0;   //未支付
    const PAY_PAYING = 1;    //支付中
    const PAY_PAYED = 2;     //支付完成

    const TYPE_NONE = 0; //普通订单
    const TYPE_AUCTION = 1; //拍卖订单
    const TYPE_PRESELL = 2; //预售订单

    public static function tableName() {
        return 'shop_order';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'status' => 'int',
            'payment_id' => 'int',
            'shipping_id' => 'int',
            'goods_amount' => '',
            'order_amount' => '',
            'discount' => '',
            'shipping_fee' => '',
            'pay_fee' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'status' => 'Status',
            'payment_id' => 'Payment Id',
            'shipping_id' => 'Shipping Id',
            'goods_amount' => 'Goods Amount',
            'order_amount' => 'Order Amount',
            'discount' => 'Discount',
            'shipping_fee' => 'Shipping Fee',
            'pay_fee' => 'Pay Fee',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function goods() {
        return $this->hasMany(OrderGoodsModel::class,  'order_id', 'id');
    }

    /**
     * @param OrderGoodsModel[] $allGoods
     * @return float
     */
    public function getGoodsAmount(array $allGoods) {
        $total = 0;
        foreach ($allGoods as $item) {
            $total += $item->getTotal();
        }
        return $total;
    }

    public function getPayment() {
        return PaymentModel::find($this->pay_id);
    }

    public function getDelivery() {
        return ShippingModel::find($this->delivery_id);
    }

    public function createOrder() {
        $carts = CartModel::getAllGoods();
        $total = 0;
        foreach ($carts as $item) {
            $total += $item->getTotal();
        }
        $this->goods_amount = $total;
        $this->shipping_fee = 0;//$this->getDelivery()->getFee();
        $this->pay_fee = 0;//$this->getPayment()->getFee();
        $this->user_id = auth()->id();
        $this->save();
        foreach ($carts as $item) {
            OrderGoodsModel::addCartGoods($this->id, $item);
        }
        return true;
    }

    public function pay() {
        $this->getPayment()->pay($this);
    }

    public function addGoods($goods) {

    }

    public function setAddress(AddressModel $address) {

    }

    public function setPayment(PaymentModel $payment) {

    }

    public function setShipping() {

    }

    /**
     * @param CartModel[] $goods_list
     * @return OrderModel
     */
    public static function preview(array $goods_list) {
        $model = new static;
        $total = 0;
        foreach ($goods_list as $item) {
            $total += $item->getTotal();
        }
        $model->goods_amount = $total;
        $model->order_amount = $total;
        return $model;
    }
}