<?php
declare(strict_types=1);
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\GoodsSimpleModel;
use Module\Shop\Domain\Repositories\CartRepository;

class CartController extends Controller {

    public function indexAction() {
        $like_goods = GoodsSimpleModel::limit(7)->all();
        $cart = CartRepository::load();
        return $this->sendWithShare()->show(compact('like_goods', 'cart'));
    }

    public function addAction(int $goods, int $amount = 1) {
        try {
            CartRepository::addGoods($goods, $amount);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(null, '加入购物车成功！');
    }

    public function updateAction(int $id, int $amount) {
        try {
            CartRepository::updateAmount($id, $amount);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function deleteAction(int $id) {
        try {
            CartRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function miniAction() {
        $this->layout = '';
        $cart = CartRepository::load();
        return $this->show(compact('cart'));
    }

}