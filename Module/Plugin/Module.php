<?php
declare(strict_types=1);
namespace Module\Plugin;

use Domain\AdminMenu;
use Zodream\Route\Controller\Module as BaseModule;
use Module\Plugin\Domain\Migrations\CreatePluginTables;

class Module extends BaseModule {

    public function getMigration() {
        return new CreatePluginTables();
    }

    public function adminMenu(): array {
        return [
            AdminMenu::build('插件管理', 'fa fa-calendar', './@admin')
        ];
    }
}