<?php
namespace Module\Shop\Domain\Models;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;
use Module\Shop\Domain\Repositories\PaymentRepository;
use Module\Shop\Domain\Repositories\ShippingRepository;
use Zodream\Helpers\Time;


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
 * @property integer $reference
 * @property integer $pay_at
 * @property integer $shipping_at
 * @property integer $receive_at
 * @property integer $finish_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property PaymentModel $payment
 * @property ShippingModel $shipping
 * @property OrderAddressModel $address
 */
class OrderModel extends Model {

    const STATUS_CANCEL = 1;
    const STATUS_INVALID = 2;
    const STATUS_UN_PAY = 10;
    const STATUS_PAYING = 12;
    const STATUS_PAID_UN_SHIP = 20;
    const STATUS_SHIPPED = 40;
    const STATUS_RECEIVED = 60;
    const STATUS_FINISH = 80;
    const STATUS_REFUNDED = 81;

    const TYPE_NONE = 0; //普通订单
    const TYPE_AUCTION = 1; //拍卖订单
    const TYPE_PRESELL = 2; //预售订单

    public static $status_list = [
        self::STATUS_UN_PAY => '待支付',
        self::STATUS_PAYING => '支付中',
        self::STATUS_SHIPPED => '待收货',
        self::STATUS_FINISH => '已完成',
        self::STATUS_CANCEL => '已取消',
        self::STATUS_INVALID => '已失效',
        self::STATUS_PAID_UN_SHIP => '待发货',
        self::STATUS_RECEIVED => '待评价',
        self::STATUS_REFUNDED => '已退款'
    ];

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
            'reference' => 'int',
            'pay_at' => 'int',
            'shipping_at' => 'int',
            'receive_at' => 'int',
            'finish_at' => 'int',
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
            'payment_id' => '支付方式',
            'payment_name' => 'Payment Name',
            'shipping_id' => '配送方式',
            'shipping_name' => 'Shipping Name',
            'goods_amount' => 'Goods Amount',
            'order_amount' => 'Order Amount',
            'discount' => 'Discount',
            'shipping_fee' => 'Shipping Fee',
            'pay_fee' => 'Pay Fee',
            'reference' => '推荐人',
            'pay_at' => 'Pay At',
            'shipping_at' => 'Shipping At',
            'receive_at' => 'Receive At',
            'finish_at' => 'Finish At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'logistics_number' => '物流单号'
        ];
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }

    public function user() {
        return $this->hasOne(config('auth.model'), 'id', 'user_id');
    }

    public function goods() {
        return $this->hasMany(OrderGoodsModel::class,  'order_id', 'id');
    }

    public function getStatusLabelAttribute() {
        return self::$status_list[$this->status];
    }

    public function getPayAtAttribute() {
        $time = $this->getAttributeSource('pay_at');
        if ($time < 1) {
            return '';
        }
        return Time::format($time);
    }

    public function getShippingAtAttribute() {
        $time = $this->getAttributeSource('shipping_at');
        if ($time < 1) {
            return '';
        }
        return Time::format($time);
    }

    public function getReceiveAtAttribute() {
        $time = $this->getAttributeSource('receive_at');
        if ($time < 1) {
            return '';
        }
        return Time::format($time);
    }

    public function getFinishAtAttribute() {
        $time = $this->getAttributeSource('finish_at');
        if ($time < 1) {
            return '';
        }
        return Time::format($time);
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

    public function pay() {
        $this->payment->pay($this);
    }

    public function getTotalAttribute() {
        return $this->goods_amount + $this->pay_fee + $this->shipping_fee - $this->discount;
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
        $this->pay_fee = PaymentRepository::getFee($payment, $this->goods);
        $this->order_amount = $this->getTotalAttribute();
        return true;
    }

    public function setShipping(ShippingModel $shipping) {
        if (empty($shipping)) {
            return false;
        }
        $this->shipping_id = $shipping->id;
        $this->shipping_name = $shipping->name;
        $this->setRelation('shipping', $shipping);
        $this->shipping_fee = ShippingRepository::getFee($shipping, $shipping->settings, $this->goods);
        $this->order_amount = $this->getTotalAttribute();
        return true;
    }

    public function createOrder() {
        $this->status = self::STATUS_UN_PAY;
        $this->order_amount = $this->getTotalAttribute();
        $this->user_id = auth()->id();
        $this->series_number = self::generateSeriesNumber();
        if (!$this->save()) {
            return false;
        }
        foreach ($this->goods as $item) {
            OrderGoodsModel::addCartGoods($this, $item);
        }
        $this->address->order_id = $this->id;
        $this->address->save();
        return true;
    }

    public function restore() {
        if ($this->id < 1) {
            return;
        }
        $this->delete();
        OrderGoodsModel::query()->where('order_id', $this->id)->delete();
        OrderAddressModel::query()->where('order_id', $this->id)->delete();
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
        $model->setRelation('goods', $goods_list);
        $model->order_amount = $model->getTotalAttribute();
        return $model;
    }

    public static function generateSeriesNumber() {
        return time();
    }


}