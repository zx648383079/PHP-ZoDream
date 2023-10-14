<?php
declare(strict_types=1);
namespace Module\CMS;

use Module\CMS\Domain\Middleware\CMSSeoMiddleware;
use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Repositories\SiteRepository;
use Module\CMS\Domain\Scene\MultiScene;
use Module\CMS\Domain\Scene\SceneInterface;
use Module\SEO\Domain\ISiteMapModule;
use Module\SEO\Domain\SiteMap;
use Zodream\Helpers\Time;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements ISiteMapModule {

    public function boot() {
        app()->scoped(SceneInterface::class, MultiScene::class);
    }

    public function getMigration() {
        return new CreateCmsTables();
    }

    public function install(): void {
        parent::install();
        $routes = config('route');
        if (empty($routes['middlewares'])) {
            $routes['middlewares'] = [CMSSeoMiddleware::class];
        } else {
            $routes['middlewares'][] = CMSSeoMiddleware::class;
        }
        config()->set('route', $routes);
    }

    public function uninstall(): void {
        parent::uninstall();
        $routes = config('route');
        if (empty($routes['middlewares'])) {
            $routes['middlewares'] = array_filter($routes['middlewares'], function ($key) {
                return $key !== CMSSeoMiddleware::class;
            });
        }
        config()->set('route', $routes);
    }

    public function openLinks(SiteMap $map) {
        $map->add(url('./'), time());
        $items = CategoryModel::query()->get('id', 'updated_at');
        foreach ($items as $item) {
            $map->add(url('./category', ['id' => $item->id]),
                $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .1);
        }
        $items = ContentModel::query()->where('cat_id', '>', 0)
            ->where('status', SiteRepository::PUBLISH_STATUS_POSTED)
            ->asArray()
            ->get('id', 'cat_id', 'model_id', 'created_at');
        foreach ($items as $item) {
            $map->add(CMSSeoMiddleware::encodeUrl($item, true),
                Time::format(intval($item['created_at'])),
                SiteMap::CHANGE_FREQUENCY_WEEKLY, .4);
        }
    }
}