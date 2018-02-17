<?php
namespace Module\Shop;

use Zodream\Route\Controller\Module as BaseModule;
use Zodream\Service\Factory;
use Zodream\Template\Engine\ParserCompiler;

class Module extends BaseModule {

    public function boot() {
        Factory::view()->setConfigs([
            'suffix' => '.html'
        ])->setEngine(ParserCompiler::class)
            ->getEngine()->registerFunc('ads', function () {
                return '';
            });
    }
}