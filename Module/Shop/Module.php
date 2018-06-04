<?php
namespace Module\Shop;

use Module\Shop\Domain\Migrations\CreateShopTables;
use Zodream\Route\Controller\Module as BaseModule;
use Zodream\Service\Factory;
use Zodream\Template\Engine\ParserCompiler;

class Module extends BaseModule {

    public function boot() {

    }

    public function getMigration() {
        return new CreateShopTables();
    }
}