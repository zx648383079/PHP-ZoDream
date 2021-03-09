<?php
namespace Module\OnlineService;

use Module\OnlineService\Domain\Migrations\CreateOnlineServiceTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {


    public function getMigration() {
        return new CreateOnlineServiceTables();
    }
}