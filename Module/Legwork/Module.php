<?php
namespace Module\Legwork;

use Module\Legwork\Domain\Migrations\CreateLegworkTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateLegworkTables();
    }
}