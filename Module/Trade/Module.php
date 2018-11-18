<?php
namespace Module\Trade;

use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateWeChatTables();
    }
}