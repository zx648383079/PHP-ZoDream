<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Cart\Cart;
use Module\Shop\Domain\Cart\ICartItem;
use Module\Shop\Domain\Cart\Store;
use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\GoodsDialogModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Models\ShippingGroupModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Domain\Models\ShippingRegionModel;
use Module\Shop\Module;
use Zodream\Helpers\Json;
use InvalidArgumentException;
use Exception;

class CartRepository {
    /**
     *
     * @param $goods
     * @param int $amount
     * @return GoodsModel
     * @throws Exception
     */
    public static function checkGoods($goods, int $amount = 1) {
        if (is_numeric($goods)) {
            $goods = GoodsModel::find($goods);
        }
        if (empty($goods)) {
            throw new Exception('商品不存在');
        }
        if ($goods->status != GoodsModel::STATUS_SALE) {
            throw new Exception(sprintf('商品【%s】已下架', $goods->name));
        }
        if (!GoodsRepository::canBuy($goods, $amount)) {
            throw new Exception(sprintf('商品【%s】库存不足', $goods->name));
        }
        return $goods;
    }

    /**
     * 验证商品属性是否正确
     * @param $id
     * @param int $amount
     * @param null $properties
     * @return array [GoodsModel, ProductModel, 规格是否对]
     * @throws Exception
     */
    public static function checkGoodsOrProduct(int $id, int $amount = 1, $properties = null) {
        $goods = GoodsDialogModel::find($id);
        if (empty($goods)) {
            throw new \Exception('商品不存在');
        }
        if ($goods->status != GoodsModel::STATUS_SALE) {
            throw new \Exception(sprintf('商品【%s】已下架', $goods->name));
        }
        if (!GoodsRepository::canBuy($goods, $amount, $properties)) {
            throw new \Exception(sprintf('商品【%s】库存不足', $goods->name));
        }
        return [$goods, null, true];
    }

    public static function addGoods($goods, int $amount = 1, $properties = null) {
        $cartItem = Module::cart()->getGoods(is_numeric($goods) ? $goods : $goods->id);
        $totalAmount = $amount;
        if ($cartItem) {
            $totalAmount += $cartItem->amount();
        }
        $goods = static::checkGoods($goods, $totalAmount);
        Module::cart()->add(CartModel::fromGoods($goods, $amount))->save();
        return true;
    }

    public static function updateGoods($goods, int $amount = 1, $properties = null) {
        $cartItem = Module::cart()->getGoods(is_numeric($goods) ? $goods : $goods->id);
        if ($amount < 1) {
            if ($cartItem) {
                Module::cart()->removeId($cartItem->getId());
            }
            return true;
        }
        $goods = static::checkGoods($goods, $amount);
        if ($cartItem) {
            Module::cart()->update($cartItem->getId(), $amount);
        } else {
            Module::cart()->add(CartModel::fromGoods($goods, $amount))->save();
        }
        return true;
    }

    public static function updateAmount(int $id, int $amount = 1) {
        $cartItem = Module::cart()->get($id);
        if (empty($cartItem)) {
            throw new Exception('购物车不存此商品');
        }
        if ($amount < 1) {
            Module::cart()->removeId($id);
            return true;
        }
        static::checkGoods($cartItem->goods, $amount);
        Module::cart()->update($id, $amount);
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
    public static function preview(array $goods_list, int $address, int $shipping, int $payment, bool $isPreview = true) {
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
    public static function checkout(int $address, int $shipping, int $payment, $cart = '', $type = 0) {
        $goods_list = static::getGoodsList($cart, $type);
        $store = new Store();
        if (!$store->frozen($goods_list)) {
            throw new InvalidArgumentException('库存不足！');
        }
        $success = false;
        try {
            $order = static::preview($goods_list, $address, $shipping, $payment, false);
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

    /**
     * 清空失效的商品
     * @return Cart
     * @throws Exception
     */
    public static function removeInvalid() {
        $cart = Module::cart();
        $ids = [];
        foreach ($cart as $group) {
            foreach ($group as $item) {
                /** @var CartModel $item */
                if ($item->invalid()) {
                    $ids[] = $item->getId();
                }
            }
        }
        $cart->remove($ids);
        return $cart;
    }
}