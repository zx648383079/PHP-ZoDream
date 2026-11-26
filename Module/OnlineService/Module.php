<?php
declare(strict_types=1);
namespace Module\OnlineService;

use Module\OnlineService\Domain\Migrations\CreateOnlineServiceTables;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {


    public function getMigration() {
        return new CreateOnlineServiceTables();
    }
}