<?php
namespace Module\Counter\Service\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController;
use Zodream\Disk\File;


class Controller extends ModuleController {

    use AdminRole;

    protected File|string $layout = '/Admin/layouts/main';

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }
}