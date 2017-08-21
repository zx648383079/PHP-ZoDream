<?php
namespace Module\Disk;

use Module\Disk\Domain\Migrations\CreateDiskTables;
use Zodream\Service\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateDiskTables();
    }
}