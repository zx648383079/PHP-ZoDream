<?php
declare(strict_types=1);
namespace Module\Plugin\Service\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController;
use Zodream\Disk\File;


class Controller extends ModuleController {

    use AdminRole;
    public File|string $layout = 'main';
    protected function getUrl(mixed $path, array $args = []): string {
        return url('./@admin/'.$path, $args);
    }

    public function findLayoutFile(): File|string {
        if ($this->layout === '') {
            return '';
        }
        return app_path()->file('UserInterface/Admin/layouts/main.php');
    }
}