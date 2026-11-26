<?php
declare(strict_types=1);
namespace Module\Counter;

use Module\Counter\Domain\Migrations\CreateCounterTables;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateCounterTables();
    }
}