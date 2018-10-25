<?php
namespace Module\OpenPlatform;

use Module\OpenPlatform\Domain\Migrations\CreateOpenPlatformTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateOpenPlatformTables();
    }

    public function invokeRoute($path) {
        $path = trim($path, '/');
        if (empty($path)) {
            return;
        }
        $uris = explode('/', $path, 2);
        $module = config('modules.'.$uris[0]);
        if (empty($module)) {
            return;
        }
        return $this->invokeModule($module, isset($uris[1]) ? 'api/'.$uris[1] : 'api');
    }
}