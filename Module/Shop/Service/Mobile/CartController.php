<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\CartModel;
use Module\Shop\Domain\Model\GoodsModel;
use Module\Shop\Domain\ShoppingCart;
use Module\Shop\Module;

class CartController extends Controller {

    public function indexAction() {
        $goods_list = Module::cart();
        return $this->show(compact('goods_list'));
    }

    public function addAction($goods, $amount = 1) {
        $goods = GoodsModel::find($goods);
        if (!$goods->canBuy($amount)) {
            return $this->jsonFailure('库存不足');
        }
        Module::cart()->addGoods($goods, $amount);
        return $this->jsonSuccess(null, '加入购物车成功！');
    }

    public function updateAction($id, $amount) {
        $cart = Module::cart()->getCart($id);
        if (!$cart->goods->canBuy($amount)) {
            return $this->jsonFailure('库存不足');
        }
        Module::cart()->addGoods($cart->goods, $amount);
        return $this->jsonSuccess();
    }

    public function deleteAction($id) {
        Module::cart()->removeCartId($id);
        return $this->jsonSuccess();
    }
}