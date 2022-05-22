<?php
declare(strict_types=1);
namespace Module\AppStore;

use Module\SEO\Domain\SiteMap;
use Zodream\Route\Controller\Module as BaseModule;
use Module\OnlineTV\Domain\Migrations\CreateTVTables;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateTVTables();
    }

    public function openLinks(SiteMap $map) {
        $map->add(url('./'), time());
    }
}