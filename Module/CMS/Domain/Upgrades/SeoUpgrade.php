<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Upgrades;

use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\ModelRepository;
use Module\CMS\Module;

/**
 * 升级支持SEO版本
 */
class SeoUpgrade {

    public function handle(): void {
        if (empty(CMSRepository::scene())) {
            (new Module())->boot();
        }
        $modelItem = ModelModel::query()->where('type', 0)->get();
        foreach ($modelItem as $item) {
            ModelRepository::batchAddField([
                [
                    'name' => 'SEO优雅链接',
                    'field' => 'seo_link',
                    'model_id' => $item['id'],
                    'is_main' => 1,
                    'is_system' => 1,
                    'is_required' => 0,
                    'type' => 'text'
                ]
            ]);
        }
        $siteItems = SiteModel::query()->get();
        foreach ($siteItems as $site) {
            $this->upgradeSite($site, $modelItem);
        }

    }

    private function upgradeSite(SiteModel $site, array $modelItem): void {
        CMSRepository::site($site);
        $scene = CMSRepository::scene();
        $scene->boot();
        ContentModel::query()->delete();
        $id = 100;
        foreach ($modelItem as $model) {
            $scene->setModel($model);
            if (!$scene->initializedModel()) {
                continue;
            }
            $scene->addField([
                'name' => 'SEO优雅链接',
                'field' => 'seo_link',
                'model_id' => $model['id'],
                'is_main' => 1,
                'is_system' => 0,
                'is_required' => 0,
                'type' => 'text'
            ]);
            $items = $scene->query()->get();
            foreach ($items as $item) {
                $id ++;
                ContentModel::query()->insert([
                    'id' => $id,
                    'title' => $item['title'],
                    'cat_id' => $item['cat_id'],
                    'model_id' => $item['model_id'],
                    'parent_id' => $item['parent_id'],
                    'seo_link' => $item['seo_link'] ?? '',
                    'status' => $item['status'],
                    'created_at' => $item['created_at']
                ]);
                $scene->query()->where('id', $item['id'])->update([
                    'id' => $id
                ]);
                $scene->extendQuery()->where('id', $item['id'])->update([
                    'id' => $id
                ]);
            }
        }
    }
}