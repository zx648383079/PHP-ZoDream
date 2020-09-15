<?php
namespace Module\Catering;

use Zodream\Route\Controller\Module as BaseModule;
use Module\Catering\Domain\Migrations\CreateCateringTables;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateCateringTables();
    }

}