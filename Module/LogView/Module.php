<?php
namespace Module\LogView;

use Module\LogView\Domain\Migrations\CreateLogTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateLogTables();
    }
}