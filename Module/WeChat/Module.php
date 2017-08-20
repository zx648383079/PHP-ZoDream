<?php
namespace Module\WeChat;

use Module\WeChat\Domain\Migrations\CreateWeChatTables;
use Zodream\Service\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateWeChatTables();
    }
}