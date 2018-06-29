<?php
namespace Module\OpenPlatform;

use Module\OpenPlatform\Domain\Migrations\CreateOpenPlatformTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateOpenPlatformTables();
    }
}