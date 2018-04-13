<?php
namespace Module\Shop\Service;

class CartController extends Controller {

    public function indexAction() {
        return $this->show();
    }

    public function checkoutAction() {
        return $this->show();
    }
}