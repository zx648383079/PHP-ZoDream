<?php
declare(strict_types=1);
namespace Module\Contact;

use Domain\MenuLoader;
use Zodream\Route\Controller\Module as BaseModule;
use Module\Contact\Domain\Migrations\CreateContactTables;

class Module extends BaseModule {

    public function getMigration() {
        return new CreateContactTables();
    }

    public function adminMenu(): array {
        return [
            MenuLoader::build('联系管理', 'fa fa-bullhorn', children: [
                MenuLoader::build('友情链接', 'fa fa-link', './@admin/friend_link'),
                MenuLoader::build('留言反馈', 'fa fa-cookie', './@admin/feedback'),
                MenuLoader::build('订阅', 'fa fa-rss', './@admin/subscribe'),
            ])
        ];
    }

}