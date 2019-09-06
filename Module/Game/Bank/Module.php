<?php
namespace Module\Game\Bank;

use Module\Game\Bank\Domain\Migrations\CreateBankTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateBankTables();
    }
}