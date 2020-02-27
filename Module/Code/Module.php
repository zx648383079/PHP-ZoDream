<?php
namespace Module\Code;

use Module\Code\Domain\Migrations\CreateCodeTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateCodeTables();
    }
}