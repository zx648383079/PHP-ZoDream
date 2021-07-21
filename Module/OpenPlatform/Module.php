<?php
declare(strict_types=1);
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

    private static bool $isBooted = false;

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
        if (str_starts_with($path, $version)) {
            $path = substr($path, strlen($version));
        }
        if (empty($path)) {
            // 参数里指定路径
            $path = $context['request']->get('method');
        }
        list($nextPath, $modulePath, $module) = $context->make('route')->tryMatchModule($path);
        if (empty($module)) {
            return;
        }
        $context['module_path'] = $modulePath;
        return $this->invokeWithPlatform($module, $nextPath, $path, $context);
    }

    /**
     * @param string $module
     * @param string $path 不包含模块路径
     * @param string $fullPath 包含模块路径
     * @param HttpContext $context
     * @return RestResponse|mixed
     * @throws \Exception
     */
    protected function invokeWithPlatform(string $module, string $path, string $fullPath, HttpContext $context) {
        app()->scoped('auth', JWTAuth::class);
        app()->scoped(JsonResponse::class, Rest::class);
        try {
            $platform = Platform::createAuto();
            if (!$platform->verifyRule($module, $fullPath)) {
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
        } catch (\TypeError $ex) {
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