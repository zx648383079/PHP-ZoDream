<?php
declare(strict_types=1);
namespace Module\Legwork;

use Module\Legwork\Domain\Migrations\CreateLegworkTables;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateLegworkTables();
    }
}