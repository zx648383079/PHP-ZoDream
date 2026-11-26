<?php
declare(strict_types=1);
namespace Module\Chat;


use Module\Chat\Domain\Migrations\CreateChatTables;
use Zodream\Route\Controller\Module as BaseModule;

final class Module extends BaseModule {

    public function getMigration() {
        return new CreateChatTables();
    }
}