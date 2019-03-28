<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\Model\CategoryModel;
use Module\ModuleController;
use Zodream\Service\Factory;
use Zodream\Template\Engine\ParserCompiler;

class Controller extends ModuleController {

    public function prepare() {
        $engine = new ParserCompiler();
        $engine->registerFunc('channel', '')
            ->registerFunc('channels', '')
            ->registerFunc('contents', '')
            ->registerFunc('content', '')
            ->registerFunc('option', '');
        Factory::view()
            ->setEngine($engine)
            ->setConfigs([
            'suffix' => '.html'
        ]);
        $categories_tree = CategoryModel::tree()->makeIdTree();
        $this->send(compact('categories_tree'));
    }
}