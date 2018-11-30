<?php
namespace Module\OpenPlatform;

use Module\OpenPlatform\Domain\Migrations\CreateOpenPlatformTables;
use Module\OpenPlatform\Domain\Model\PlatformModel;
use PhpParser\Node\Expr\Empty_;
use Zodream\Domain\Access\JWTAuth;
use Zodream\Infrastructure\Http\Output\RestResponse;
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
        if (empty($path)) {
            // 参数里指定路径
            $path = app('request')->get('method');
        }
        $uris = explode('/', $path, 2);
        if (empty($uris[0])) {
            return;
        }
        $module = config('modules.'.$uris[0]);
        if (empty($module)) {
            return;
        }
        return $this->invokeWithPlatform($module, $uris);
    }

    /**
     * @param $module
     * @param $uris
     * @return RestResponse
     * @throws \Exception
     */
    protected function invokeWithPlatform($module, $uris): RestResponse {
        app()->instance('app::class', Api::class);
        app()->register('auth', JWTAuth::class);
        $platform = PlatformModel::find(app('request')->get('appid'));
        if (empty($platform)) {
            return RestResponse::createWithAuto([
                'code' => 404,
                'message' => __('APP ID error')
            ]);
        }
        if (!$platform->verifyRest()) {
            return RestResponse::createWithAuto([
                'code' => 404,
                'message' => __('sign or encrypt error')
            ]);
        }
        app()->instance('platform', $platform);
        $data = $this->invokeModule($module, isset($uris[1]) ? 'api/' . $uris[1] : 'api');
        return $platform->ready($data);
    }
}