<?php
namespace Module\Video;

use Module\Video\Domain\Migrations\CreateVideoTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateVideoTables();
    }
}