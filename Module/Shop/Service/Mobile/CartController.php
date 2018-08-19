<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\CartModel;
use Module\Shop\Domain\Model\GoodsModel;

class CartController extends Controller {

    public function indexAction() {
        $goods_list = CartModel::getAllGoods();
        return $this->show(compact('goods_list'));
    }

    public function addAction($goods, $amount = 1) {
        $goods = GoodsModel::find($goods);
        if (!$goods->canBuy($amount)) {
            return $this->jsonFailure('库存不足');
        }
        CartModel::addGoods($goods, $amount);
        return $this->jsonSuccess(null, '加入购物车成功！');
    }

    public function updateAction($id, $amount) {
        $cart = CartModel::find($id);
        if (!$cart->goods->canBuy($amount)) {
            return $this->jsonFailure('库存不足');
        }
        $cart->updateAmount($amount);
        return $this->jsonSuccess();
    }

    public function deleteAction($id) {
        CartModel::deleteById($id);
        return $this->jsonSuccess();
    }
}