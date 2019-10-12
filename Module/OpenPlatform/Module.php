<?php
namespace Module\OpenPlatform;

use Module\OpenPlatform\Domain\Migrations\CreateOpenPlatformTables;
use Module\OpenPlatform\Domain\Model\PlatformModel;
use Zodream\Domain\Access\JWTAuth;
use Zodream\Infrastructure\Http\Output\RestResponse;
use Zodream\Route\Controller\Module as BaseModule;
use Zodream\Service\Api;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateOpenPlatformTables();
    }

    public function invokeRoute($path) {
        config()->set('app.rewrite', false); // 禁用重写
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
     * @return RestResponse|mixed
     * @throws \Exception
     */
    protected function invokeWithPlatform($module, $uris) {
        app()->instance('app::class', Api::class);
        app()->register('auth', JWTAuth::class);
        $platform = PlatformModel::findByAppId(app('request')->get('appid'));
        if (empty($platform)) {
            app('response')->setStatusCode(404);
            return RestResponse::createWithAuto([
                'code' => 404,
                'message' => __('APP ID error')
            ]);
        }
        if (!$platform->verifyRest()) {
            app('response')->setStatusCode(404);
            return RestResponse::createWithAuto([
                'code' => 404,
                'message' => __('sign or encrypt error')
            ]);
        }
        app()->instance('platform', $platform);
        $data = $this->invokeModule($module, isset($uris[1]) ? 'api/' . $uris[1] : 'api');
        if ($data instanceof RestResponse) {
            return $platform->ready($data);
        }
        return $data;
    }
}