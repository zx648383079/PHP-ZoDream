<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Model\CartModel;
use Module\Shop\Domain\Model\GoodsModel;
use Module\Shop\Module;

class CartController extends Controller {

    public function indexAction() {
        $cart = Module::cart();
        return $this->render($cart);
    }

    public function addAction($goods, $amount = 1) {
        $goods = GoodsModel::find($goods);
        if (!$goods || !$goods->canBuy($amount)) {
            return $this->renderFailure('库存不足');
        }
        Module::cart()->add(CartModel::fromGoods($goods, $amount))->save();
        return $this->render(Module::cart());
    }

    public function updateAction($id, $amount) {
        Module::cart()->update($id, $amount);
        return $this->render(Module::cart());
    }

    public function deleteAction($id) {
        Module::cart()->remove($id);
        return $this->render(Module::cart());
    }
}