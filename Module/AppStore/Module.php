<?php
declare(strict_types=1);
namespace Module\AppStore;

use Module\AppStore\Domain\Migrations\CreateAppTables;
use Module\SEO\Domain\SiteMap;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateAppTables();
    }

    public function openLinks(SiteMap $map) {
        $map->add(url('./'), time());
    }
}