<?php
namespace Module\Note;

use Module\SEO\Domain\SiteMap;
use Zodream\Route\Controller\Module as BaseModule;
use Module\Note\Domain\Migrations\CreateNoteTables;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateNoteTables();
    }

    public function openLinks(SiteMap $map) {
        $map->add(url('./'), time());
    }
}