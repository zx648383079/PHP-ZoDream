<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Model\CartModel;
use Module\Shop\Domain\Model\GoodsModel;

class CartController extends Controller {

    public function indexAction() {
        $like_goods = GoodsModel::limit(7)->select(GoodsModel::THUMB_MODE)->all();
        return $this->sendWithShare()->show(compact('like_goods'));
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