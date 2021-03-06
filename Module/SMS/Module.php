<?php
namespace Module\SMS;

use Module\SMS\Domain\Migrations\CreateSmsTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateSmsTables();
    }
}