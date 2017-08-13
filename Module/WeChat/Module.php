<?php
namespace Module\WeChat;

use Module\WeChat\Domain\Migrations\CreateWeChatTables;
use Zodream\Service\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function insert() {
        $this->getMigration()->up();
    }

    public function uninstall() {
        $this->getMigration()->up();
    }

    public function getMigration() {
        return new CreateWeChatTables();
    }
}