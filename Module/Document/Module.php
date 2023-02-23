<?php
namespace Module\Document;

use Module\Document\Domain\Migrations\CreateDocumentTables;
use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\PageModel;
use Module\Document\Domain\Model\ProjectModel;
use Module\SEO\Domain\ISiteMapModule;
use Module\SEO\Domain\SiteMap;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements ISiteMapModule {

    public function getMigration() {
        return new CreateDocumentTables();
    }

    public function openLinks(SiteMap $map) {
        $map->add(url('./'), time());
        $items = ProjectModel::where('status', ProjectModel::STATUS_PUBLIC)->get('id', 'updated_at');
        $itemId = [];
        foreach ($items as $item) {
            $itemId[] = $item->id;
            $map->add(url('./project', ['id' => $item->id]),
                $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
        }
        if (empty($itemId)) {
            return;
        }
//        $items = ApiModel::query()->whereIn('project_id', $itemId)->get('id', 'updated_at');
//        foreach ($items as $item) {
//            $map->add(url('./api', ['id' => $item->id]),
//                $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
//        }
        $items = PageModel::query()->whereIn('project_id', $itemId)->get('id', 'updated_at');
        foreach ($items as $item) {
            $map->add(url('./page', ['id' => $item->id]),
                $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
        }
    }
}