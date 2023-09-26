<?php
namespace Module\Forum\Service\Admin;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\ModuleController;
use Zodream\Disk\File;


class Controller extends ModuleController {

    use CheckRole;

    public File|string $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => 'forum_admin'
        ];
    }

    protected function getUrl(mixed $path, array $args = []): string {
        return url('./@admin/'.$path, $args);
    }

}