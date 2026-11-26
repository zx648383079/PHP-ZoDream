<?php
declare(strict_types=1);
namespace Module\Game\TCG;

use Module\Game\TCG\Domain\Migrations\CreateTheCardGameTables;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateTheCardGameTables();
    }
}