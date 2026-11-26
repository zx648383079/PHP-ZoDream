<?php
declare(strict_types=1);
namespace Module\Cas;

use Module\Cas\Domain\Migrations\CreateCasTables;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateCasTables();
    }
}