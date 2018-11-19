<?php
namespace Module\Trade;

use Module\Task\Domain\Migrations\CreateTradeTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateTradeTables();
    }
}