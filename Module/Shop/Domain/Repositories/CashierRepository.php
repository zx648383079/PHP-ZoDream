<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Exception;
use InvalidArgumentException;
use Module\Shop\Domain\Cart\ICartItem;
use Module\Shop\Domain\Cart\Store;
use Module\Shop\Domain\Entities\GoodsEntity;
use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\CouponLogModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Module;
use Zodream\Helpers\Json;

/**
 *
 */
final class CashierRepository {

    public static function store(int $status = 0):  Store {
        $store = new Store();
        $store->setStatus($status);
        return $store;
    }

    public static function formatAddress(int $user, mixed $address): ?AddressModel {
        if (is_array($address) && isset($address['id']) && $address['id'] > 0) {
            $address = $address['id'];
        }
        if (is_array($address)) {
            $data = new AddressModel($address);
        } else {
            $data = AddressModel::where('id', intval($address))
                ->where('user_id', $user)->first();
        }
        if (empty($data) || $data->region_id < 1 || !$data->tel || !$data->address) {
            return null;
        }
        return $data;
//        return [
//            'goods' => [
//                [
//                    'name' => '',
//                    'activity' => '',
//                    'items' => [
//                        [
//                            'id' => '',
//                            'type' => '',
//                            'goods_id' => '',
//                            'product_id' => '',
//                            'price' => '',
//                            'amount' => '',
//                            'selected_activity' => '',
//                            'attribute_id' => '',
//                            'attribute_value' => '',
//                            'goods' => '',
//                        ]
//                    ]
//                ]
//            ],
//            'address' => '',
//            'coupon' => '',
//            'shipping' => '',
//            'payment' => '',
//            'activity' => [],
//            'total' => '',
//            'discount' => '',
//        ];
    }

    public static function formatCoupon(int $user, int $coupon, string $couponCode): ?CouponLogModel {
        if ($coupon > 0) {
            return CouponLogModel::where('user_id', $user)
                ->where('id', $coupon)
                ->where('order_id', 0)->first();
        }
        if (empty($couponCode)) {
            return null;
        }
        return CouponLogModel::where(function ($query) use ($user) {
            $query->where('user_id', $user)
                ->orWhere('user_id', 0);
        })->where('serial_number', $couponCode)->where('order_id', 0)->first();
    }

    public static function getShipping(string $code): ShippingModel|null
    {
        return ShippingModel::where('code', $code)->first();
    }

    public static function getPayment(string $code): PaymentModel|null
    {
        if ($code === PaymentModel::COD_CODE) {
            return new PaymentModel([
                'code' => PaymentModel::COD_CODE,
                'name' => '货到付款'
            ]);
        }
        return PaymentModel::where('code', $code)->first();
    }

    public static function shipList(int $userId, array $goods, mixed $addressId, int $type = 0) {
        $address = self::formatAddress($userId, $addressId);
        if (empty($address)) {
            throw new Exception('地址错误');
        }
        $goods_list = static::getGoodsList($goods, $type);
        $data = ShippingRepository::getByAddress($address);
        if (empty($data)) {
            throw new Exception('当前地址不在配送范围内');
        }
        foreach ($data as $item) {
            $item->shipping_fee = ShippingRepository::getFee($item, $item->settings, $goods_list);
        }
        return $data;
    }

    public static function paymentList(array $goods, string $shipping, int $type = 0) {
        $data = PaymentModel::query()->get();
        $ship = static::getShipping($shipping);
        if ($ship && $ship->cod_enabled > 0) {
            $data[] = static::getPayment(PaymentModel::COD_CODE);
        }
        return $data;
    }

    public static function couponList(int $userId, array $goods, int $type = 0) {
        $goods_list = static::getGoodsList($goods, $type);
        return CouponRepository::getUserUseGoods($userId, $goods_list);
    }

    /**
     * @param int $userId
     * @param array $goods_list
     * @param int $address
     * @param string $shipping
     * @param string $payment
     * @param int $coupon
     * @param bool $isPreview // 如果只验证，则配送方式和支付方式可空
     * @return OrderModel
     * @throws Exception
     */
    public static function preview(int $userId, array $goods_list,
                                   mixed $address,
                                   string $shipping,
                                   string $payment,
                                   int $coupon = 0,
                                   string $coupon_code = '',
                                   bool $isPreview = true) {
        if (empty($goods_list)) {
            throw new InvalidArgumentException('请选择结算的商品');
        }
        $order = OrderModel::preview($goods_list);
        if (empty($address)) {
            throw new Exception('收货地址无效或不完整');
        }
        $address = self::formatAddress($userId, $address);
        if (empty($address) || !$order->setAddress($address)) {
            throw new InvalidArgumentException('请选择收货地址');
        }
        $coupon = self::formatCoupon($userId, $coupon, $coupon_code);
        if (!empty($coupon) && !CouponRepository::canUse($coupon->coupon, $goods_list)) {
            throw new InvalidArgumentException('优惠卷不能使用在此订单');
            // TODO 减去优惠金额
        }
        if (!empty($payment) && !$order->setPayment(static::getPayment($payment)) && !$isPreview) {
            throw new InvalidArgumentException('请选择支付方式');
        }
        if (!empty($shipping)) {
            $ship = static::getShipping($shipping);
            if (empty($ship)) {
                throw new InvalidArgumentException('配送方式不存在');
            }
            $shipGroup = ShippingRepository::getGroup($ship->id, $address->region_id);
            if (empty($shipGroup)) {
                throw new InvalidArgumentException('当前地址不支持此配送方式');
            }
            $ship->settings = $shipGroup;
            if (!$order->setShipping($ship) && !$isPreview) {
                throw new InvalidArgumentException('请选择配送方式');
            }
            if (!empty($payment) && !$ship->canUsePayment($order->payment)) {
                throw new InvalidArgumentException(
                    sprintf('当前配送方式不支持【%s】支付方式', $order->payment->name)
                );
            }
        } elseif (!$isPreview) {
            throw new InvalidArgumentException('请选择配送方式');
        }
        return $order;
    }

    /**
     * 结算
     * @param int $userId
     * @param int|array $address
     * @param string $shipping
     * @param string $payment
     * @param int $coupon
     * @param string $coupon_code
     * @param string $cart
     * @param int $type
     * @return OrderModel
     * @throws Exception
     */
    public static function checkout(int $userId, mixed $address, string $shipping, string $payment,
                                    int $coupon = 0, string $coupon_code = '', $cart = '', int $type = 0) {
        $goods_list = static::getGoodsList($cart, $type);
        $store = self::store(Store::STATUS_ORDER);
        if (!$store->frozen($goods_list)) {
            throw new InvalidArgumentException('库存不足！');
        }
        $success = false;
        try {
            $order = static::preview($userId, $goods_list, $address, $shipping, $payment, $coupon, $coupon_code, false);
            if ($order->createOrder($userId)) {
                $success = true;
                $store->clear();
            } else {
                $order->restore();
            }
        } catch (Exception $ex) {
            $store->restore();
            throw $ex;
        }
        if (!$success) {
            throw new InvalidArgumentException('操作失败，请重试');
        }
        if ($type < 1) {
            CartRepository::load()->remove(...$goods_list);
        }
        return $order;
    }

    /**
     * 获取结算商品
     * @param mixed $cart
     * @param int $type
     * @return ICartItem[]
     * @throws \Exception
     */
    public static function getGoodsList(mixed $cart = '', int $type = 0) {
        if ($type < 1) {
            $cart_ids = is_array($cart) ? $cart : explode('-', $cart);
            return CartRepository::load()->filter(function ($item) use ($cart_ids) {
                return in_array($item['id'], $cart_ids);
            });
        }
        if (empty($cart)) {
            return [];
        }
        if (!is_array($cart)) {
            $cart = Json::decode($cart);
        }
        $data = [];
        foreach ($cart as $item) {
            if (!isset($item['goods']) && !isset($item['goods_id'])) {
                continue;
            }
            $goods = CartRepository::getGoods(intval($item['goods_id'] ?? $item['goods']));
            if (empty($goods) || $goods->status !== GoodsEntity::STATUS_SALE) {
                continue;
            }
            $properties = $item['properties'] ?? '';
            if (empty($properties) && isset($item['attribute_id'])) {
                $properties = $item['attribute_id'];
            }
            $data[] = CartRepository::formatCartItem($goods->id, $properties,
                max(1, isset($item['amount']) ? intval($item['amount']) : 1));
        }
        return $data;
    }
}