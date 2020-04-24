<?php
namespace Module\Code;

use Module\Code\Domain\Migrations\CreateCodeTables;
use Module\SEO\Domain\SiteMap;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateCodeTables();
    }

    public function openLinks(SiteMap $map) {
        $map->add(url('./'), time());
    }
}