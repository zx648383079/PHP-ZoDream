<?php
declare(strict_types=1);
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Repositories\CartRepository;

class CartController extends Controller {

    public function indexAction() {
        $cart = CartRepository::load();
        return $this->show(compact('cart'));
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
}