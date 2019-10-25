<?php
namespace Module\Auth;

use Module\Auth\Domain\Migrations\CreateAuthTables;
use Zodream\Route\Controller\Concerns\UseModulePackage;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    use UseModulePackage;

    public function getMigration() {
        return new CreateAuthTables();
    }
}