<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\CartModel;
use Module\Shop\Domain\Model\GoodsModel;
use Module\Shop\Module;

class CartController extends Controller {

    public function indexAction() {
        $cart = Module::cart();
        return $this->show(compact('cart'));
    }

    public function addAction($goods, $amount = 1) {
        $goods = GoodsModel::find($goods);
        if (!$goods->canBuy($amount)) {
            return $this->jsonFailure('库存不足');
        }
        Module::cart()->add(CartModel::fromGoods($goods, $amount))->save();
        return $this->jsonSuccess(null, '加入购物车成功！');
    }

    public function updateAction($id, $amount) {
        Module::cart()->update($id, $amount);
        return $this->jsonSuccess();
    }

    public function deleteAction($id) {
        Module::cart()->remove($id);
        return $this->jsonSuccess();
    }
}