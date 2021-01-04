<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\ModuleController;
use Zodream\Infrastructure\Error\Exception;

use Zodream\Template\Engine\ParserCompiler;

class Controller extends ModuleController {

    public function prepare() {
        $dir = view()->getDirectory()
            ->directory(CMSRepository::theme());
        if (!$dir->exist()) {
            throw new Exception('THEME IS ERROR!');
        }
        view()
            ->setDirectory($dir)
            ->setEngine(FuncHelper::register(new ParserCompiler()))
            ->setConfigs([
                'suffix' => '.html'
            ]);
        $categories_tree = FuncHelper::channels(['tree' => true]);
        $this->send(compact('categories_tree'));
    }
}