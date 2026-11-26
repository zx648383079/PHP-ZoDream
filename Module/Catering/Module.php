<?php
declare(strict_types=1);
namespace Module\Catering;

use Zodream\Route\Controller\Module as BaseModule;
use Module\Catering\Domain\Migrations\CreateCateringTables;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateCateringTables();
    }

}