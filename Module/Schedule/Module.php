<?php
declare(strict_types=1);
namespace Module\Schedule;

use Module\Schedule\Domain\Migrations\CreateScheduleTables;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateScheduleTables();
    }
}