<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Repositories\CartRepository;
use Module\Shop\Domain\Repositories\GoodsRepository;
use Module\Shop\Module;

class CartController extends Controller {

    public function indexAction() {
        $cart = Module::cart();
        return $this->render($cart);
    }

    public function addAction($goods, $amount = 1, $properties = null) {
        try {
            list($goods, $product, $success) = CartRepository::checkGoodsOrProduct($goods, $amount, $properties);
            if (!$success) {
                return $this->render([
                    'dialog' => true,
                    'data' => GoodsRepository::formatProperties($goods)
                ]);
            }
            CartRepository::addGoods($goods, $amount, $properties);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render(Module::cart());
    }

    public function updateGoodsAction($goods, $amount = 1, $properties = null) {
        try {
            list($goods, $product, $success) = CartRepository::checkGoodsOrProduct($goods, $amount, $properties);
            if (!$success) {
                return $this->render([
                    'dialog' => true,
                    'data' => GoodsRepository::formatProperties($goods)
                ]);
            }
            CartRepository::updateGoods($goods, $amount, $properties);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
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

    public function clearAction() {
        Module::cart()->clear();
        return $this->render(Module::cart());
    }
}