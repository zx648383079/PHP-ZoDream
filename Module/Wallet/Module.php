<?php
declare(strict_types=1);
namespace Module\Wallet;

use Module\Wallet\Domain\Migrations\CreateWalletTables;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateWalletTables();
    }
}