<?php
declare(strict_types=1);
namespace Module\Template\Service\Admin;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\ModuleController;
use Zodream\Disk\File;

abstract class Controller extends ModuleController {

    use CheckRole;

    protected File|string $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => 'template_admin'
        ];
    }

    protected function getUrl(string $path, array $args = []) {
        return url('./@admin/'.$path, $args);
    }
}