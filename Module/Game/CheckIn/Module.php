<?php
declare(strict_types=1);
namespace Module\Game\CheckIn;

use Domain\MenuLoader;
use Module\Game\CheckIn\Domain\Migrations\CreateCheckInTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateCheckInTables();
    }

    public function adminMenu(): array {
        return [
            MenuLoader::build('签到管理', 'fa fa-calendar-check', './@admin/setting')
        ];
    }
}