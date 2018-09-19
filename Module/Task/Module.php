<?php
namespace Module\Task;

use Module\Task\Domain\Migrations\CreateTaskTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateTaskTables();
    }
}