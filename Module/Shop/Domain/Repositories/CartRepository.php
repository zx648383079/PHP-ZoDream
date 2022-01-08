<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Cart\Cart;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\GoodsDialogModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Module;
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