<?php
declare(strict_types=1);
namespace Module\Disk;

use Module\Disk\Domain\FFmpeg;
use Module\Disk\Domain\Migrations\CreateDiskTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateDiskTables();
    }

    public function adminMenu(): array {
        return [
            [
                '文件管理',
                false,
                'fa fa-folder-open',
                [
                    [
                        '资源管理器',
                        './@admin/explorer',
                        'fa fa-folder'
                    ],
                    [
                        '上传管理器',
                        './@admin/explorer/storage',
                        'fa fa-file-upload'
                    ],
                ],
            ]
        ];
    }
}