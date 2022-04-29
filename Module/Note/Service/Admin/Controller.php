<?php
declare(strict_types=1);
namespace Module\Note\Service\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController;
use Zodream\Disk\File;

class Controller extends ModuleController {

    use AdminRole;

    public File|string $layout = '/Admin/layouts/main';

    protected function getUrl(string $path, array $args = []) {
        return url('./@admin/'.$path, $args);
    }

   public function redirectWithMessage(mixed $url, string $message, int $time = 4, int $status = 40) {
       return $this->show('@root/Admin/prompt', compact('url', 'message', 'time'));
   }
}