<?php
namespace Module\Finance;

use Module\Finance\Domain\Migrations\CreateFinanceTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateFinanceTables();
    }
}