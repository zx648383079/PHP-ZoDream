<?php
namespace Module\Legwork\Service\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController;
use Zodream\Disk\File;

class Controller extends ModuleController {

    use AdminRole;

    public File|string $layout = '/Admin/layouts/main';

    protected function getUrl(mixed $path, array $args = []): string {
        return url('./@admin/'.$path, $args);
    }
}