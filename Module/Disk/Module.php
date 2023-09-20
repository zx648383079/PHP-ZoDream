<?php
declare(strict_types=1);
namespace Module\Disk;

use Domain\AdminMenu;
use Module\Disk\Domain\Migrations\CreateDiskTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateDiskTables();
    }

    public function adminMenu(): array {
        return [
            AdminMenu::build('文件管理', 'fa fa-folder-open', children: [
                AdminMenu::build('资源管理器', 'fa fa-folder', './@admin/explorer'),
                AdminMenu::build('上传管理器', 'fa fa-file-upload', './@admin/explorer/storage'),
            ]),
        ];
    }
}