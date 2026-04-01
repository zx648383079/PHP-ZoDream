<?php
namespace Module\Wallet;

use Module\Wallet\Domain\Migrations\CreateWalletTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateWalletTables();
    }
}