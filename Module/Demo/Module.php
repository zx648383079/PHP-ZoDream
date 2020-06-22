<?php
namespace Module\Demo;

use Module\Demo\Domain\Migrations\CreateDemoTables;
use Zodream\Route\Controller\Concerns\StaticAssetsRoute;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {
    use StaticAssetsRoute;

    public function getMigration() {
        return new CreateDemoTables();
    }
}