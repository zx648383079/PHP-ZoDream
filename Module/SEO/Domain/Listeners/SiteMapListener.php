<?php
namespace Module\SEO\Domain\Listeners;

use Module\SEO\Domain\SiteMap;
use Zodream\Route\Router;
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