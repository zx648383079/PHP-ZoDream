<?php
declare(strict_types=1);
namespace Module\MessageService;

use Domain\AdminMenu;
use Module\MessageService\Domain\Migrations\CreateMessageServiceTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateMessageServiceTables();
    }

    public function adminMenu(): array {
        return [
            AdminMenu::build('消息服务管理', 'fa fa-mail-bulk', children: [
                AdminMenu::build('短信配置', 'fa fa-cog', './@admin/option/sms'),
                AdminMenu::build('邮箱配置', 'fa fa-cog', './@admin/option/mail'),
                AdminMenu::build('模板管理', 'fa fa-paper-plane', './@admin/template'),
                AdminMenu::build('发送记录', 'fa fa-history', './@admin/log'),
            ])
        ];
    }
}