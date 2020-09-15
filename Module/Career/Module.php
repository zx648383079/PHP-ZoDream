<?php
namespace Module\Career;

use Zodream\Route\Controller\Module as BaseModule;
use Module\Career\Domain\Migrations\CreateCareerTables;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateCareerTables();
    }

}