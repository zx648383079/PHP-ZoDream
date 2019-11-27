<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Module;
use Zodream\Helpers\Json;
use InvalidArgumentException;
use Exception;

class CartRepository {
    /**
     * @param $goods
     * @param int $amount
     * @return GoodsModel
     * @throws Exception
     */
    public static function checkGoods($goods, $amount = 1) {
        if (is_numeric($goods)) {
            $goods = GoodsModel::find($goods);
        }
        if (empty($goods)) {
            throw new Exception('商品不存在');
        }
        if ($goods->status != GoodsModel::STATUS_SALE) {
            throw new Exception(sprintf('商品【%s】已下架', $goods->name));
        }
        if (!$goods->canBuy($amount)) {
            throw new Exception(sprintf('商品【%s】库存不足', $goods->name));
        }
        return $goods;
    }

    public static function addGoods($goods, $amount = 1, $properties = null) {
        $goods = static::checkGoods($goods, $amount);
        Module::cart()->add(CartModel::fromGoods($goods, $amount))->save();
        return true;
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
    public static function preview($goods_list, $address, $shipping, $payment, $isPreview = true) {
        if (empty($goods_list)) {
            throw new InvalidArgumentException('请选择结算的商品');
        }
        $order = OrderModel::preview($goods_list);
        if (!$order->setAddress(AddressModel::findWithAuth($address))) {
            throw new InvalidArgumentException('请选择收货地址');
        }
        if (!$isPreview && $payment > 0 && !$order->setPayment(PaymentModel::find($payment))) {
            throw new InvalidArgumentException('请选择支付方式');
        }
        if (!$isPreview && $shipping > 0 &&!$order->setShipping(ShippingModel::find($shipping))) {
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
    public static function checkout($address, $shipping, $payment, $cart = '', $type = 0) {
        $goods_list = static::getGoodsList($cart, $type);
        $order = static::preview($goods_list, $address, $shipping, $payment, false);
        if (!$order->createOrder()) {
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
     * @return array
     * @throws \Exception
     */
    public static function getGoodsList($cart = '', $type = 0) {
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
            $goods = GoodsModel::find(isset($item['goods_id']) ? $item['goods_id'] : $item['goods']);
            if (empty($goods)) {
                continue;
            }
            $data[] = CartModel::fromGoods($goods,
                max(1, isset($item['amount']) ? intval($item['amount']) : 1));
        }
        return $data;
    }
}