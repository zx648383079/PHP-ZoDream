<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\CategoryModel;
use Module\ModuleController;
use Zodream\Service\Factory;
use Zodream\Template\Engine\ParserCompiler;

class Controller extends ModuleController {

    public function prepare() {
        Factory::view()
            ->setEngine(FuncHelper::register(new ParserCompiler()))
            ->setConfigs([
            'suffix' => '.html'
        ]);
        $categories_tree = CategoryModel::tree()->makeIdTree();
        $this->send(compact('categories_tree'));
    }
}