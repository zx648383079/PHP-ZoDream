<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\CartModel;
use Module\Shop\Domain\Model\GoodsModel;

class CartController extends Controller {

    public function indexAction() {
        //$goods_list = CartModel::getAllGoods();
        return $this->show(compact('goods_list'));
    }

    public function addAction($goods, $amount = 1) {
        $goods = GoodsModel::find($goods);
        CartModel::addGoods($goods, $amount);
        return $this->jsonSuccess();
    }

    public function updateAction($id, $amount) {
        $cart = CartModel::find($id);
        $cart->number = $amount;
        $cart->save();
        return $this->jsonSuccess();
    }

    public function deleteAction($id) {
        $cart = CartModel::find($id);
        $cart->delete();
        return $this->jsonSuccess();
    }
}