<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Listeners;

use Domain\Middlewares\UrlRouterMiddleware;
use Module\SEO\Domain\SiteMap;
use Zodream\Infrastructure\Contracts\HttpContext;
use Zodream\Route\ModuleRoute;
use Zodream\ThirdParty\API\Search;

class SiteMapListener {
    public function __construct($event) {
        self::create();
        if (empty($event) || !is_object($event) || app()->isDebug()) {
            return;
        }
        try {
            if (!method_exists($event, 'getUrl')) {
                return;
            }
            $urls = $event->getUrl();
            if (empty($urls)) {
                return;
            }
            if (!is_array($urls)) {
                $urls = [$urls];
            }
            $api = new Search([
                'site' => request()->host(),
                'token' => config('baidu.ziyuan')
            ]);
            $res = $api->putBaiDu($urls);
            logger(var_export($res, true));
        } catch (\Exception $ex) {
            logger($ex->getMessage());
        }
    }

    public static function create() {
        $rewritable = config('route.rewrite');
        $configure = require (string)config()->configPath('route');
        if (!empty($configure) && isset($configure['rewrite'])) {
            config()->set('route.rewrite', $configure['rewrite']);
        }
        url()->useCustomScript();
        UrlRouterMiddleware::enable(false);
	    $map = new SiteMap();
        $map->add(url('/'), time());
        $modules = config('route.modules');
        /** @var ModuleRoute $route */
        $route = app(ModuleRoute::class);
	    foreach ($modules as $path => $module) {
	        if (empty($module)) {
	            continue;
            }
            $route->module($path, function () use ($map, $module, $route) {
                $instance = $route->moduleInstance($module, app(HttpContext::class));
                if (method_exists($instance, 'openLinks')) {
                    call_user_func([$instance, 'openLinks'], $map);
                }
            }, $modules);
        }
        $map->toXml();
        url()->useCustomScript(false);
        config()->set('route.rewrite', $rewritable);
        return $map;
    }
}