<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\Model\CategoryModel;
use Module\ModuleController;
use Zodream\Service\Factory;

class Controller extends ModuleController {

    public function prepare() {
        Factory::view()->setConfigs([
            'suffix' => '.html'
        ]);
        $categories_tree = CategoryModel::tree()->makeIdTree();
        $this->send(compact('categories_tree'));
    }
}