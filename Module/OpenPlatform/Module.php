<?php
namespace Module\OpenPlatform;

use Module\Auth\Domain\Events\TokenCreated;
use Module\OpenPlatform\Domain\Listeners\TokenListener;
use Module\OpenPlatform\Domain\Migrations\CreateOpenPlatformTables;
use Module\OpenPlatform\Domain\Platform;
use Zodream\Disk\File;
use Zodream\Domain\Access\JWTAuth;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Infrastructure\Contracts\Response\JsonResponse;
use Zodream\Route\Controller\Module as BaseModule;
use Zodream\Route\Response\Rest;
use Zodream\Route\Response\RestResponse;

class Module extends BaseModule {

    private static $isBooted = false;

    public function getMigration() {
        return new CreateOpenPlatformTables();
    }

    public function invokeRoute($path, HttpContext $context) {
        if (self::$isBooted) {
            return;
        }
        self::$isBooted = true;
        config()->set('route.rewrite', false); // 禁用重写
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
            $path = $context['request']->get('method');
        }
        list($path, $modulePath, $module) = $context->make('route')->tryMatchModule($path);
        if (empty($module)) {
            return;
        }
        url()->setModulePath($modulePath);
        return $this->invokeWithPlatform($module, $path, $context);
    }

    /**
     * @param $module
     * @param $path
     * @param HttpContext $context
     * @return RestResponse|mixed
     * @throws \Exception
     */
    protected function invokeWithPlatform($module, $path, HttpContext $context) {
        app()->scoped('auth', JWTAuth::class);
        app()->scoped(JsonResponse::class, Rest::class);
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
            $data = $this->invokeModule($module, !empty($path) ? 'api/' . $path : 'api');
            $context['response']->allowCors();
            if ($data instanceof RestResponse) {
                return $platform->ready($data);
            }
            return $data;
        } catch (\Exception $ex) {
            logger($ex);
            $context['response']->statusCode(404);
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