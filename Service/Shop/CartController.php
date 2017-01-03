<?php
namespace Service\Shop;


use Domain\ShoppingCart;

class CartController extends Controller {
    public function indexAction() {
        $cart = new ShoppingCart();
        return $this->show([
            'cart' => $cart
        ]);
    }
}