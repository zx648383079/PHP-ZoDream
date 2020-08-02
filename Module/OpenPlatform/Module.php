<?php
namespace Module\OpenPlatform;

use Module\Auth\Domain\Events\TokenCreated;
use Module\OpenPlatform\Domain\Listeners\TokenListener;
use Module\OpenPlatform\Domain\Migrations\CreateOpenPlatformTables;
use Module\OpenPlatform\Domain\Platform;
use Zodream\Disk\File;
use Zodream\Domain\Access\JWTAuth;
use Zodream\Infrastructure\Http\Output\RestResponse;
use Zodream\Route\Controller\Module as BaseModule;
use Zodream\Service\Api;

class Module extends BaseModule {

    private static $isBooted = false;

    public function getMigration() {
        return new CreateOpenPlatformTables();
    }

    public function invokeRoute($path) {
        if (self::$isBooted) {
            return;
        }
        self::$isBooted = true;
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
        url()->setModulePath($uris[0]);
        return $this->invokeWithPlatform($module, $uris, $path);
    }

    /**
     * @param $module
     * @param $uris
     * @param $path
     * @return RestResponse|mixed
     * @throws \Exception
     */
    protected function invokeWithPlatform($module, $uris, $path) {
        app()->instance('app::class', Api::class);
        app()->register('auth', JWTAuth::class);
        try {
            $platform = Platform::createAuto();
            if (!$platform->verifyRule($module, $path)) {
                throw new \Exception(__('The URL you requested was disabled'));
            }
            if (!$platform->verifyRest()) {
                throw new \Exception(__('sign or encrypt error'));
            }
            $platform->useCustomToken();
            Platform::enterPlatform($platform);
            event()->listen(TokenCreated::class, TokenListener::class);
            $data = $this->invokeModule($module, isset($uris[1]) ? 'api/' . $uris[1] : 'api');
            if ($data instanceof RestResponse) {
                return $platform->ready($data);
            }
            return $data;
        } catch (\Exception $ex) {
            app('response')->setStatusCode(404);
            return RestResponse::createWithAuto([
                'code' => 404,
                'message' => $ex->getMessage()
            ]);
        }
    }

    /**
     * 获取当前模板文件
     * @param $path
     * @return File
     */
    public static function viewFile($path) {
        return new File(__DIR__.'/UserInterface/'. trim($path, '/'));
    }

}