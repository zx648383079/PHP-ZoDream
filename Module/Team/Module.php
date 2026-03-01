<?php
declare(strict_types=1);
namespace Module\Team;

use Module\Team\Domain\Migrations\CreateTeamTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateTeamTables();
    }
}