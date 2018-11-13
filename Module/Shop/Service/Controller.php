<?php
namespace Module\Shop\Service;

use Module\ModuleController;
use Module\Shop\Domain\Model\ArticleModel;
use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\NavigationModel;

class Controller extends ModuleController {
    public $layout = 'main';
    
    public function prepare() {

    }

    public function sendWithShare() {
        $categories_tree = CategoryModel::cacheTree();
        $this->send(compact('categories_tree'));
        return $this;
    }
}