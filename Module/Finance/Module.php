<?php
namespace Module\Finance;

use Module\Disk\Domain\Migrations\CreateFinanceTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateFinanceTables();
    }
}