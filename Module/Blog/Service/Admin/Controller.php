<?php
namespace Module\Blog\Service\Admin;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\ModuleController;
use Zodream\Disk\File;

class Controller extends ModuleController {

    use CheckRole;

    public File|string $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    protected function getUrl(mixed$path, array $args = []) {
        return url('./@admin/'.$path, $args);
    }

}