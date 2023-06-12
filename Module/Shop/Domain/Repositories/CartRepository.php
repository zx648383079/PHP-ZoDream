<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Domain\Providers\MemoryCacheProvider;
use Module\Shop\Domain\Cart\Cart;
use Module\Shop\Domain\Cart\CartItem;
use Module\Shop\Domain\Cart\DatabaseCart;
use Module\Shop\Domain\Cart\ICartItem;
use Module\Shop\Domain\Entities\GoodsEntity;
use Module\Shop\Domain\Entities\ProductEntity;
use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\GoodsDialogModel;
use Module\Shop\Domain\Models\GoodsModel;
use Exception;
use Module\Shop\Domain\Models\GoodsSimpleModel;

final class CartRepository {

    private static mixed $instance = null;
    public static function cache(): MemoryCacheProvider {
        return MemoryCacheProvider::getInstance();
    }

    public static function load(): Cart {
        if (empty(self::$instance)) {
            self::$instance = new DatabaseCart();
        }
        return self::$instance;
    }

    public static function getGoods(int $goodId): GoodsSimpleModel|null {
        return self::cache()->getOrSet('goods', $goodId, function () use ($goodId) {
            return GoodsSimpleModel::query()->where('id', $goodId)->first();
        });
    }

    public static function formatCartItem(int $goodsId, array|string $properties, int $amount): ICartItem {
        if (!is_array($properties)) {
            $properties = AttributeRepository::formatPostProperties($properties);
        }
        $box = AttributeRepository::getProductAndPriceWithProperties($properties, $goodsId);
        return new CartItem([
            'goods_id' => $goodsId,
            'product_id' => !empty($box['product']) ? $box['product']['id'] : 0,
            'amount' => $amount,
            'price' => GoodsRepository::finalPrice($goodsId, $amount, $properties),
            'attribute_id' => implode(',', $properties),
            'attribute_value' => $box['properties_label'],
        ]);
    }

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
        if ($goods->status != GoodsEntity::STATUS_SALE) {
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
     * @param int[] $properties
     * @return array [GoodsModel, ProductModel, 规格是否对]
     * @throws Exception
     */
    public static function checkGoodsOrProduct(int $id, int $amount = 1, array $properties = []) {
        $goods = GoodsDialogModel::find($id);
        if (empty($goods)) {
            throw new \Exception('商品不存在');
        }
        if ($goods->status != GoodsEntity::STATUS_SALE) {
            throw new \Exception(sprintf('商品【%s】已下架', $goods->name));
        }
        $box = AttributeRepository::getProductAndPriceWithProperties($properties, $goods->id);
        if (empty($box['product']) && ProductEntity::where('goods_id', $id)->count() > 0) {
            return [$goods, $box['product'], false];
        }
        if (!GoodsRepository::canBuy($goods, $amount, $properties)) {
            throw new \Exception(sprintf('商品【%s】库存不足', $goods->name));
        }
        return [$goods, $box['product'], true];
    }

    public static function addGoods($goods, int $amount = 1, array $properties = []): bool {
        $cart = self::load();
        $cartItem = $cart->tryAdd(is_numeric($goods) ? $goods : $goods->id, $properties, $amount);
        list($_, $_, $success) = self::checkGoodsOrProduct($goods, $cartItem->amount(), $properties);
        if (!$success) {
            return false;
        }
        // $cart->add(CartModel::fromGoods($goods, $amount))->save();
        $cart->save();
        return true;
    }

    public static function updateGoods($goods, int $amount = 1, array $properties = []): bool {
        $cart = self::load();
        $cartItem = $cart->tryUpdate(is_numeric($goods) ? $goods : $goods->id, $properties, $amount);
        if (!$cartItem) {
            return true;
        }
        list($_, $_, $success) = self::checkGoodsOrProduct($goods, $cartItem->amount(), $properties);
        if (!$success) {
            return false;
        }
        return true;
    }

    public static function updateAmount(int $id, int $amount = 1) {
        $cart = self::load();
        $cartItem = $cart->get($id);
        if (empty($cartItem)) {
            throw new Exception('购物车不存此商品');
        }
        if ($amount < 1) {
            $cart->removeId($id);
            return true;
        }
        self::checkGoodsOrProduct($cartItem->goodsId(), $cartItem->amount(), explode(',', $cartItem->properties()));
        $cart->update($id, $amount);
        return true;
    }

    /**
     * 清空失效的商品
     * @return Cart
     * @throws Exception
     */
    public static function removeInvalid() {
        $cart = self::load();
        $ids = [];
        foreach ($cart as $item) {
            /** @var CartModel $item */
            if ($item->invalid()) {
                $ids[] = $item->getId();
            }
        }
        $cart->remove($ids);
        $cart->save();
        return $cart;
    }

    public static function remove(int $id) {
        $cart = self::load();
        $cart->removeId($id);
    }
}