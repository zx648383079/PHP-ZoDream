<?php
namespace Module\CMS;

use Module\CMS\Domain\Migrations\CreateCmsTables;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Scene\MultiScene;
use Module\CMS\Domain\Scene\SceneInterface;
use Module\CMS\Domain\Scene\SingleScene;
use Module\SEO\Domain\SiteMap;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function boot() {
        app()->scoped(SceneInterface::class, SingleScene::class);
    }

    public function getMigration() {
        return new CreateCmsTables();
    }

    public function openLinks(SiteMap $map) {
        $map->add(url('./'), time());
        $items = CategoryModel::query()->get('id', 'updated_at');
        foreach ($items as $item) {
            $map->add(url('./category', ['id' => $item->id]),
                $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
        }
        $items = ContentModel::query()->where('cat_id', '>', 0)
            ->get('id', 'cat_id', 'model_id', 'updated_at');
        foreach ($items as $item) {
            $map->add(url('./content',
                ['id' => $item->id, 'category' => $item->cat_id, 'model' => $item->model_id]),
                $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
        }
    }
}