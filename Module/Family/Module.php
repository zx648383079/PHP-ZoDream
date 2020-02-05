<?php
namespace Module\Family;

use Zodream\Route\Controller\Module as BaseModule;
use Module\Family\Domain\Migrations\CreateFamilyTables;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateFamilyTables();
    }

}