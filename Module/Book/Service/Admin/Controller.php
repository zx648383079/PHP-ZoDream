<?php
namespace Module\Book\Service\Admin;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\ModuleController;
use Zodream\Disk\File;


class Controller extends ModuleController {

    use CheckRole;

    public File|string $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => 'book_admin'
        ];
    }

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }

    public function redirectWithMessage(mixed $url, string $message, int $time = 4, int $status = 404) {
        response()->statusCode($status);
        return $this->show('@root/Admin/prompt', compact('url', 'message', 'time', 'status'));
    }
}