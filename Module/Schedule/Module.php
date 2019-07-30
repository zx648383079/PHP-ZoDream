<?php
namespace Module\Schedule;

use Module\Schedule\Domain\Migrations\CreateScheduleTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateScheduleTables();
    }
}