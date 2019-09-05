<?php
namespace Module\Game\CheckIn;

use Module\Game\CheckIn\Domain\Migrations\CreateCheckInTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateCheckInTables();
    }
}