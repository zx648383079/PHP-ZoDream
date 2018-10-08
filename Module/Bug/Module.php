<?php
namespace Module\Bug;

use Module\Bug\Domain\Migrations\CreateBugTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateBugTables();
    }
}