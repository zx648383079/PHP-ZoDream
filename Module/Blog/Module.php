<?php
namespace Module\Blog;

use Module\Blog\Domain\Helpers\RouterHelper;
use Module\Blog\Domain\Middleware\BlogSeoMiddleware;
use Module\Blog\Domain\Migrations\CreateBlogTables;
use Module\SEO\Domain\ISiteMapModule;
use Module\SEO\Domain\SiteMap;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements ISiteMapModule {

    public function getMigration() {
        return new CreateBlogTables();
    }

    public function install(): void {
        parent::install();
        $routes = config('route');
        if (empty($routes['middlewares'])) {
            $routes['middlewares'] = [BlogSeoMiddleware::class];
        } else {
            $routes['middlewares'][] = BlogSeoMiddleware::class;
        }
        config()->set('route', $routes);
    }

    public function uninstall(): void {
        parent::uninstall();
        $routes = config('route');
        if (empty($routes['middlewares'])) {
            $routes['middlewares'] = array_filter($routes['middlewares'], function ($key) {
                return $key !== BlogSeoMiddleware::class;
            });
        }
        config()->set('route', $routes);
    }

    public function openLinks(SiteMap $map) {
        RouterHelper::openLinks($map);
    }
}