<?php
declare(strict_types=1);
namespace Module\Counter\Service\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController;
use Zodream\Disk\File;


class Controller extends ModuleController {

    use AdminRole;

    protected File|string $layout = '/Admin/layouts/main';

    protected function getUrl(string $path, array $args = []) {
        return url('./@admin/'.$path, $args);
    }
}