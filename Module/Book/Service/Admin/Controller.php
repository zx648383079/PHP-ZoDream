<?php
namespace Module\Book\Service\Admin;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\ModuleController;


class Controller extends ModuleController {

    use CheckRole;

    public $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => 'book_admin'
        ];
    }

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }

    public function redirectWithMessage($url, $message, $time = 4, $status = 404) {
        return $this->show('@root/Admin/prompt', compact('url', 'message', 'time'));
    }
}