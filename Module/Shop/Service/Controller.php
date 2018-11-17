<?php
namespace Module\Shop\Service;

use Module\ModuleController;
use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\ShoppingCart;

class Controller extends ModuleController {
    public $layout = 'main';
    
    public function prepare() {

    }

    public function sendWithShare() {
        $categories_tree = CategoryModel::cacheTree();
        $cart = new ShoppingCart();
        $this->send(compact('categories_tree', 'cart'));
        return $this;
    }
}