<?php
declare(strict_types=1);
namespace Module\Disk;

use Domain\MenuLoader;
use Module\Disk\Domain\Migrations\CreateDiskTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateDiskTables();
    }

    public function adminMenu(): array {
        return [
            MenuLoader::build('文件管理', 'fa fa-folder-open', children: [
                MenuLoader::build('资源管理器', 'fa fa-folder', './@admin/explorer'),
                MenuLoader::build('上传管理器', 'fa fa-file-upload', './@admin/explorer/storage'),
            ]),
        ];
    }
}