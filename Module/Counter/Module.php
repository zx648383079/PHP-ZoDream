<?php
namespace Module\Counter;

use Module\Auth\Domain\Migrations\CreateAuthTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateAuthTables();
    }
}