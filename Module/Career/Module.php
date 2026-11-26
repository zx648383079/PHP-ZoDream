<?php
declare(strict_types=1);
namespace Module\Career;

use Zodream\Route\Controller\Module as BaseModule;
use Module\Career\Domain\Migrations\CreateCareerTables;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateCareerTables();
    }

}