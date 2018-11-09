<?php
namespace Module\OpenPlatform;

use Module\OpenPlatform\Domain\Migrations\CreateOpenPlatformTables;
use Zodream\Domain\Access\JWTAuth;
use Zodream\Route\Controller\Module as BaseModule;
use Zodream\Service\Api;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateOpenPlatformTables();
    }

    public function invokeRoute($path) {
        $path = trim($path, '/');
        if (empty($path)) {
            return;
        }
        $version = app()->version().'/';
        // 去除API版本号
        if (strpos($path, $version) === 0) {
            $path = substr($path, strlen($version));
        }
        $uris = explode('/', $path, 2);
        $module = config('modules.'.$uris[0]);
        if (empty($module)) {
            return;
        }
        app()->instance('app::class', Api::class);
        app()->register('auth', JWTAuth::class);
        return $this->invokeModule($module, isset($uris[1]) ? 'api/'.$uris[1] : 'api');
    }
}