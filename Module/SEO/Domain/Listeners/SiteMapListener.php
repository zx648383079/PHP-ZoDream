<?php
namespace Module\SEO\Domain\Listeners;

use Module\SEO\Domain\SiteMap;
use Zodream\Route\Router;

class SiteMapListener {
    public function __construct($event) {
        self::create();
    }

    public static function create() {
        url()->useCustomScript();
	    $map = new SiteMap();
        $map->add(url('/'), time());
        $modules = config()->moduleConfigs('Home')['modules'];
	    foreach ($modules as $path => $module) {
            app(Router::class)->module($path, function () use ($map, $module) {
                $instance = app(Router::class)->moduleInstance($module);
                if (method_exists($instance, 'openLinks')) {
                    call_user_func([$instance, 'openLinks'], $map);
                }
            }, $modules);
        }
        $map->toXml();
        url()->useCustomScript(false);
        return $map;
    }
}