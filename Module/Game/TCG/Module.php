<?php
namespace Module\Game\TCG;

use Module\Game\TCG\Domain\Migrations\CreateTheCardGameTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateTheCardGameTables();
    }
}