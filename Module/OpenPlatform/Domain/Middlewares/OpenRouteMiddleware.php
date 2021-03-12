<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Domain\Middlewares;


use Module\Auth\Domain\Events\TokenCreated;
use Module\OpenPlatform\Domain\Listeners\TokenListener;
use Module\OpenPlatform\Domain\Platform;
use Zodream\Domain\Access\JWTAuth;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Infrastructure\Contracts\Response\JsonResponse;
use Zodream\Route\Response\Rest;
use Zodream\Route\Response\RestResponse;
use Zodream\Service\Middleware\MiddlewareInterface;

class OpenRouteMiddleware implements MiddlewareInterface {

    public function handle(HttpContext $context, callable $next)
    {
        $app = app();
        $app->scoped('auth', JWTAuth::class);
        $app->scoped(JsonResponse::class, Rest::class);
        try {
            $platform = Platform::createAuto();
            if (!$platform->verifyRule(get_class($context['module']), $context->path())) {
                throw new \Exception(__('The URL you requested was disabled'));
            }
            if (!$platform->verifyRest()) {
                throw new \Exception(__('sign or encrypt error'));
            }
            $platform->useCustomToken();
            Platform::enterPlatform($platform);
            event()->listen(TokenCreated::class, TokenListener::class);
            $data = $next();
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
}