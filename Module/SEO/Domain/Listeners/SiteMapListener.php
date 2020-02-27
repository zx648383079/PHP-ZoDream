<?php
namespace Module\SEO\Domain\Listeners;

use Module\SEO\Domain\SiteMap;
use Module\Blog\Domain\Model\BlogModel;
use Zodream\Route\Router;

class SiteMapListener {
    public function __construct($event) {
        self::create();
    }

    public static function create() {
        url()->useCustomScript();
	    $map = new SiteMap();
        $map->add(url('/'), time());
        $map->add(url('/blog'), time());
        $map->add(url('/cms'), time());
        $map->add(url('/doc'), time());
        $modules = config()->moduleConfigs('Home')['modules'];
	    app(Router::class)->module('blog', function () use ($map) {
            $blog_list = BlogModel::where('open_type', '<>', BlogModel::OPEN_DRAFT)->orderBy('id', 'desc')->get('id', 'updated_at');
            foreach ($blog_list as $item) {
                $map->add($item->url,
                    $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
            }
        }, $modules);
        $map->toXml();
        url()->useCustomScript(false);
        return $map;
    }
}