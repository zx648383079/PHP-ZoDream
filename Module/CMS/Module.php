<?php
namespace Module\CMS;

use Module\CMS\Domain\Migrations\CreateCmsTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateCmsTables();
    }
}