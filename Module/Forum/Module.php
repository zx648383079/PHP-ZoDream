<?php
namespace Module\Forum;

use Module\Forum\Domain\Migrations\CreateForumTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateForumTables();
    }
}