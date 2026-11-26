<?php
declare(strict_types=1);
namespace Module\Video;

use Module\Video\Domain\Migrations\CreateVideoTables;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateVideoTables();
    }
}