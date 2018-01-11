<?php
namespace Module\Shop\Cart;

use Module\Chat\Domain\Migrations\CreateChatTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateChatTables();
    }
}