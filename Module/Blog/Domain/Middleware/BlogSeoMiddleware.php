<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Middleware;

use Module\Blog\Domain\Helpers\RouterHelper;
use Module\Blog\Module;
use Module\Blog\Service\HomeController;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\ModuleRoute;
use Zodream\Route\OnlyRoute;
use Zodream\Service\Middleware\MiddlewareInterface;

/**
 * 增加优雅链接支持
 */
class BlogSeoMiddleware implements MiddlewareInterface{
    protected static function modulePath(): string {
        static $path = null;
        if (is_string($path)) {
            return $path;
        }
        $res = app(HttpContext::class)->make(ModuleRoute::class)->getModulePath(Module::class);
        if (empty($res) || $res === ModuleRoute::DEFAULT_ROUTE) {
            $path = '/';
        } else {
            $path = $res.'/';
        }
        return $path;
    }

    public function handle(HttpContext $context, callable $next) {
        $path = $context->path();
        if (str_starts_with($path, 'open/') || str_contains($path, '/admin/')) {
            return $next($context);
        }
        if (!str_starts_with($path, static::modulePath()) || strpos($path, '/',
                strlen(static::modulePath())) !== false) {
            return $next($context);
        }
        $blogId = RouterHelper::linkId(substr($path, 5));
        if ($blogId < 1) {
            return $next($context);
        }
        return new OnlyRoute(HomeController::class, 'detailAction', [
            'id' => $blogId,
        ], [
            trim(static::modulePath(), '/') => Module::class
        ]);
    }

    public static function encodeUrl(int|string $id): string {
        $link = RouterHelper::idLink($id);
        if (empty($link)) {
            return url(static::modulePath(), ['id' => $id]);
        }
        return url(sprintf('%s%s', static::modulePath(), $link));
    }
}