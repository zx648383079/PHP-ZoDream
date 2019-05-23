<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Module;
use Module\ModuleController;
use Module\Template\Domain\Model\Base\OptionModel;
use Zodream\Service\Factory;
use Zodream\Template\Engine\ParserCompiler;

class Controller extends ModuleController {

    public function prepare() {
        Factory::view()
            ->setDirectory(Factory::view()->getDirectory()
                ->directory(Module::theme()))
            ->setEngine(FuncHelper::register(new ParserCompiler()))
            ->setConfigs([
                'suffix' => '.html'
            ]);
        $categories_tree = FuncHelper::channels(['tree' => true]);
        $this->send(compact('categories_tree'));
    }
}