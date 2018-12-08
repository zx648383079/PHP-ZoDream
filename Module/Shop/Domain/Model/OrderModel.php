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
 * @property string $series_number
 * @property integer $user_id
 * @property integer $status
 * @property integer $payment_id
 * @property string $payment_name
 * @property integer $shipping_id
 * @property string $shipping_name
 * @property float $goods_amount
 * @property float $order_amount
 * @property float $discount
 * @property float $shipping_fee
 * @property float $pay_fee
 * @property integer $created_at
 * @property integer $updated_at
 * @property PaymentModel $payment
 * @property ShippingModel $shipping
 * @property OrderAddressModel $address
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
            'series_number' => 'required|string:0,100',
            'user_id' => 'required|int',
            'status' => 'int',
            'payment_id' => 'int',
            'payment_name' => 'string:0,30',
            'shipping_id' => 'int',
            'shipping_name' => 'string:0,30',
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
            'series_number' => 'Series Number',
            'user_id' => 'User Id',
            'status' => 'Status',
            'payment_id' => 'Payment Id',
            'payment_name' => 'Payment Name',
            'shipping_id' => 'Shipping Id',
            'shipping_name' => 'Shipping Name',
            'goods_amount' => 'Goods Amount',
            'order_amount' => 'Order Amount',
            'discount' => 'Discount',
            'shipping_fee' => 'Shipping Fee',
            'pay_fee' => 'Pay Fee',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function user() {
        return $this->hasOne(config('auth.model'), 'id', 'user_id');
    }

    public function goods() {
        return $this->hasMany(OrderGoodsModel::class,  'order_id', 'id');
    }

    public function getStatusLabelAttribute() {
        return '待付款';
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

    public function payment() {
        return $this->hasOne(PaymentModel::class, 'id', 'payment_id');
    }

    public function address() {
        return $this->hasOne(OrderAddressModel::class, 'order_id', 'id');
    }

    public function shipping() {
        return $this->hasOne(ShippingModel::class, 'id', 'shipping_id');
    }

    public function createOrder() {
        $carts = CartModel::getAllGoods();
        $total = 0;
        foreach ($carts as $item) {
            $total += $item->getTotalAttribute();
        }
        $this->goods_amount = $total;
        $this->order_amount = $total;
        $this->shipping_fee = $this->shipping->getFee();
        $this->pay_fee = $this->payment->getFee();
        $this->user_id = auth()->id();
        $this->series_number = self::generateSeriesNumber();
        if (!$this->save()) {
            return false;
        }
        foreach ($carts as $item) {
            OrderGoodsModel::addCartGoods($this->id, $item);
        }
        $this->address->order_id = $this->id;
        $this->address->save();
        return true;
    }

    public function pay() {
        $this->payment->pay($this);
    }

    public function addGoods($goods) {

    }

    public function setAddress(AddressModel $address) {
        if (empty($address)) {
            return false;
        }
        $this->setRelation('address', OrderAddressModel::converter($address));
        return true;
    }

    public function setPayment(PaymentModel $payment) {
        if (empty($payment)) {
            return false;
        }
        $this->payment_id = $payment->id;
        $this->payment_name = $payment->name;
        $this->setRelation('payment', $payment);
        return true;
    }

    public function setShipping(ShippingModel $shipping) {
        if (empty($shipping)) {
            return false;
        }
        $this->shipping_id = $shipping->id;
        $this->shipping_name = $shipping->name;
        $this->setRelation('shipping', $shipping);
        return true;
    }

    /**
     * @param CartModel[] $goods_list
     * @return OrderModel
     */
    public static function preview(array $goods_list) {
        $model = new static;
        $total = 0;
        foreach ($goods_list as $item) {
            $total += $item->getTotalAttribute();
        }
        $model->goods_amount = $total;
        $model->order_amount = $total;
        return $model;
    }

    public static function generateSeriesNumber() {
        return time();
    }
}