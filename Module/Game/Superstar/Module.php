<?php
namespace Module\Game\Superstar;

use Module\Game\Superstar\Domain\Migrations\CreateSuperstarTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateSuperstarTables();
    }
}