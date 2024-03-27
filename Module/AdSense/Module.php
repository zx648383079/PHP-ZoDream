<?php
declare(strict_types=1);
namespace Module\AdSense;

use Domain\MenuLoader;
use Module\AdSense\Domain\Migrations\CreateAdTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateAdTables();
    }

    public function adminMenu(): array {
        return [
            MenuLoader::build('广告管理', 'fa fa-ad', children: [
                MenuLoader::build('广告列表', 'fa fa-link', './@admin/ad'),
                MenuLoader::build('广告位列表', 'fa fa-cookie', './@admin/ad/position'),
            ])
        ];
    }
}