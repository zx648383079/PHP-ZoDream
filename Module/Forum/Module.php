<?php
namespace Module\Forum;

use Module\Forum\Domain\Migrations\CreateForumTables;
use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadModel;
use Module\SEO\Domain\ISiteMapModule;
use Module\SEO\Domain\SiteMap;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements ISiteMapModule {

    public function getMigration() {
        return new CreateForumTables();
    }

    public function openLinks(SiteMap $map) {
        $map->add(url('./'), time());
        $items = ForumModel::orderBy('id', 'desc')
            ->get('id', 'updated_at');
        foreach ($items as $item) {
            $map->add(url('./forum', ['id' => $item->id]),
                $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
        }
        $items = ThreadModel::orderBy('id', 'desc')
            ->get('id', 'updated_at');
        foreach ($items as $item) {
            $map->add(url('./thread', ['id' => $item->id]),
                $item->updated_at, SiteMap::CHANGE_FREQUENCY_WEEKLY, .8);
        }
    }
}