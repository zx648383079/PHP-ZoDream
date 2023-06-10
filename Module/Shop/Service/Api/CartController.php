<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\GoodsSimpleModel;
use Module\Shop\Domain\Repositories\AttributeRepository;
use Module\Shop\Domain\Repositories\CartRepository;
use Module\Shop\Domain\Repositories\GoodsRepository;

class CartController extends Controller {

    public function indexAction() {
        $cart = CartRepository::load();
        return $this->render($cart);
    }

    public function addAction(int $goods, int $amount = 1, $properties = null) {
        try {
            $properties = AttributeRepository::formatPostProperties($properties);
            if (!CartRepository::addGoods($goods, $amount, $properties)) {
                return $this->render([
                    'dialog' => true,
                    'data' => GoodsRepository::getDialog($goods)
                ]);
            }
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render(CartRepository::load());
    }

    public function updateGoodsAction(int $goods, int $amount = 1, $properties = null) {
        try {
            $properties = AttributeRepository::formatPostProperties($properties);
            if (!CartRepository::updateGoods($goods, $amount, $properties)) {
                return $this->render([
                    'dialog' => true,
                    'data' => GoodsRepository::getDialog($goods)
                ]);
            }
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render(CartRepository::load());
    }

    public function updateAction($id, $amount) {
        try {
            CartRepository::updateAmount($id, $amount);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render(CartRepository::load());
    }

    public function deleteAction(int|array $id) {
        $cart = CartRepository::load();
        $cart->remove($id);
        $cart->save();
        return $this->render($cart);
    }

    public function clearAction() {
        $cart = CartRepository::load();
        $cart->clear();
        $cart->save();
        return $this->render($cart);
    }

    public function deleteInvalidAction() {
        try {
            return $this->render(
                CartRepository::removeInvalid()
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function recommendAction() {
        $like_goods = GoodsSimpleModel::limit(7)->all();
        return $this->renderData($like_goods);
    }
}