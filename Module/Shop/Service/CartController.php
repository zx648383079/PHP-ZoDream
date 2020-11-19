<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Module\Shop\Domain\Repositories\CartRepository;
use Module\Shop\Module;

class CartController extends Controller {

    public function indexAction() {
        $like_goods = GoodsSimpleModel::limit(7)->all();
        $cart = Module::cart();
        return $this->sendWithShare()->show(compact('like_goods', 'cart'));
    }

    public function addAction($goods, $amount = 1) {
        try {
            CartRepository::addGoods($goods, $amount);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(null, '加入购物车成功！');
    }

    public function updateAction($id, $amount) {
        Module::cart()->update($id, $amount);
        return $this->renderData();
    }

    public function deleteAction($id) {
        Module::cart()->remove($id);
        return $this->renderData();
    }

    public function miniAction() {
        $this->layout = false;
        $cart = Module::cart();
        return $this->show(compact('cart'));
    }

}