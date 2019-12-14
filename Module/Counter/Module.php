<?php
namespace Module\Counter;

use Module\Counter\Domain\Migrations\CreateCounterTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateCounterTables();
    }
}