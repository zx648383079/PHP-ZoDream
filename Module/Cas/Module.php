<?php
namespace Module\Cas;

use Module\Cas\Domain\Migrations\CreateCasTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateCasTables();
    }
}