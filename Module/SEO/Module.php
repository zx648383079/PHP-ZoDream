<?php
declare(strict_types=1);
namespace Module\SEO;

use Domain\MenuLoader;
use Zodream\Route\Controller\Module as BaseModule;
use Module\SEO\Domain\Migrations\CreateSEOTables;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateSEOTables();
    }

    public function adminMenu(): array {
        return [
            MenuLoader::build('系统管理', 'fa fa-cogs', children: [
                MenuLoader::build('基本设置', 'fa fa-list', './@admin/setting'),
                MenuLoader::build('缓存管理', 'fa fa-cookie', './@admin/cache'),
                MenuLoader::build('生成SiteMap', 'fa fa-map', './@admin/home/sitemap'),
                MenuLoader::build('数据备份', 'fa fa-hdd', './@admin/sql'),
            ])
        ];
    }
}