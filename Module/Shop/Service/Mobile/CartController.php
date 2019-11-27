<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Repositories\CartRepository;
use Module\Shop\Module;

class CartController extends Controller {

    public function indexAction() {
        $cart = Module::cart();
        return $this->show(compact('cart'));
    }

    public function addAction($goods, $amount = 1) {
        try {
            CartRepository::addGoods($goods, $amount);
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
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