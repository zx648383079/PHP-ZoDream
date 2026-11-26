<?php
declare(strict_types=1);
namespace Module\Finance;

use Module\Finance\Domain\Migrations\CreateFinanceTables;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateFinanceTables();
    }
}