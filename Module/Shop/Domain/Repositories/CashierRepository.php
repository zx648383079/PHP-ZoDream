<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Exception;
use InvalidArgumentException;
use Module\Shop\Domain\Cart\ICartItem;
use Module\Shop\Domain\Cart\Store;
use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Module;
use Zodream\Helpers\Json;

final class CashierRepository {

    public static function shipList(array $goods, int $address, int $type = 0) {
        $goods_list = static::getGoodsList($goods, $type);
        $data = ShippingRepository::getByAddress(AddressModel::findWithAuth($address));
        if (empty($data)) {
            throw new Exception('当前地址不在配送范围内');
        }
        foreach ($data as $item) {
            $item->shipping_fee = ShippingRepository::getFee($item, $item->settings, $goods_list);
        }
        return $data;
    }

    public static function paymentList(array $goods, int $shipping, int $type = 0) {
        $data = PaymentModel::query()->get();
        return $data;
    }

    public static function couponList(array $goods, int $type = 0) {
        $goods_list = static::getGoodsList($goods, $type);
        return CouponRepository::getMyUseGoods($goods_list);
    }

    /**
     * @param $goods_list
     * @param $address
     * @param $shipping
     * @param $payment
     * @param bool $isPreview // 如果只验证，则配送方式和支付方式可空
     * @return OrderModel
     * @throws \Exception
     */
    public static function preview(array $goods_list,
                                   int $address,
                                   int $shipping,
                                   int $payment,
                                   int $coupon = 0,
                                   bool $isPreview = true) {
        if (empty($goods_list)) {
            throw new InvalidArgumentException('请选择结算的商品');
        }
        $order = OrderModel::preview($goods_list);
        if (empty($address)) {
            throw new Exception('请选择收货地址');
        }
        $address = AddressModel::findWithAuth($address);
        if (!$order->setAddress($address)) {
            throw new InvalidArgumentException('请选择收货地址');
        }
        if ($payment > 0 && !$order->setPayment(PaymentModel::find($payment)) && !$isPreview) {
            throw new InvalidArgumentException('请选择支付方式');
        }
        if ($shipping > 0) {
            $ship = ShippingModel::find($shipping);
            if (empty($ship)) {
                throw new InvalidArgumentException('配送方式不存在');
            }
            $shipGroup = ShippingRepository::getGroup($shipping, $address->region_id);
            if (empty($shipGroup)) {
                throw new InvalidArgumentException('当前地址不支持此配送方式');
            }
            $ship->settings = $shipGroup;
            if (!$order->setShipping($ship) && !$isPreview) {
                throw new InvalidArgumentException('请选择配送方式');
            }
            if ($payment > 0 && !$ship->canUsePayment($order->payment)) {
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
     * @param $address
     * @param $shipping
     * @param $payment
     * @param string $cart
     * @param int $type
     * @return OrderModel
     * @throws \Exception
     */
    public static function checkout(int $address, int $shipping, int $payment, int $coupon = 0, $cart = '', $type = 0) {
        $goods_list = static::getGoodsList($cart, $type);
        $store = new Store();
        if (!$store->frozen($goods_list)) {
            throw new InvalidArgumentException('库存不足！');
        }
        $success = false;
        try {
            $order = static::preview($goods_list, $address, $shipping, $payment, $coupon, false);
            if ($order->createOrder()) {
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
            Module::cart()->remove(...$goods_list);
        }
        return $order;
    }

    /**
     * 获取结算商品
     * @param string $cart
     * @param int $type
     * @return ICartItem[]
     * @throws \Exception
     */
    public static function getGoodsList($cart = '', int $type = 0) {
        if ($type < 1) {
            $cart_ids = is_array($cart) ? $cart : explode('-', $cart);
            return Module::cart()->filter(function ($item) use ($cart_ids) {
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
            $goods = GoodsModel::find($item['goods_id'] ?? $item['goods']);
            if (empty($goods)) {
                continue;
            }
            $data[] = CartModel::fromGoods($goods,
                max(1, isset($item['amount']) ? intval($item['amount']) : 1));
        }
        return $data;
    }
}