<?php
namespace Module\Note;

use Domain\AdminMenu;
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

    public function adminMenu(): array {
        return [
            AdminMenu::build('便签管理', 'fa fa-comment', './@admin/note')
        ];
    }
}