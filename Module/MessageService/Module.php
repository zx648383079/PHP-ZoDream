<?php
declare(strict_types=1);
namespace Module\MessageService;

use Domain\MenuLoader;
use Module\MessageService\Domain\Migrations\CreateMessageServiceTables;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateMessageServiceTables();
    }

    public function adminMenu(): array {
        return [
            MenuLoader::build('消息服务管理', 'fa fa-mail-bulk', children: [
                MenuLoader::build('模板管理', 'fa fa-paper-plane', './@admin/template'),
                MenuLoader::build('发送记录', 'fa fa-history', './@admin/log'),
                MenuLoader::build('短信配置', 'fa fa-cog', './@admin/option/sms'),
                MenuLoader::build('邮箱配置', 'fa fa-cog', './@admin/option/mail'),
            ])
        ];
    }
}