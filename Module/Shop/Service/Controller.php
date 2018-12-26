<?php
namespace Module\Shop\Service;

use Module\ModuleController;
use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Module;

class Controller extends ModuleController {
    public $layout = 'main';
    
    public function prepare() {

    }

    public function sendWithShare() {
        $categories_tree = CategoryModel::cacheTree();
        $cart = Module::cart();
        $this->send(compact('categories_tree', 'cart'));
        return $this;
    }

    public function redirectWithAuth() {
        return $this->redirect(['./member/login', 'redirect_uri' => url()->full()]);
    }
}