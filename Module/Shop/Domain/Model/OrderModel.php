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
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $sign
 * @property integer $status
 * @property integer $pay_status
 * @property integer $shipping_status
 * @property float $goods_amount
 * @property integer $pay_id
 * @property float $pay_fee
 * @property integer $delivery_id
 * @property float $shipping_fee
 * @property float $amount
 * @property integer $user_id
 * @property string $consignee
 * @property string $tel
 * @property string $address
 * @property string $remark
 * @property integer $type
 * @property integer $pay_at
 * @property integer $shipping_at
 * @property integer $create_at
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
        return PaymentModel::findOne($this->pay_id);
    }

    public function getDelivery() {
        return DeliveryModel::findOne($this->delivery_id);
    }

    public function createOrder() {
        $carts = CartModel::getAllGoods();
        $total = 0;
        foreach ($carts as $item) {
            $total += $item->getTotal();
        }
        $this->goods_amount = $total;
        $this->shipping_fee = $this->getDelivery()->getFee();
        $this->pay_fee = $this->getPayment()->getFee();
        $this->create_at = time();
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
}