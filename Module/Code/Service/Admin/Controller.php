<?php
namespace Module\Code\Service\Admin;

use Module\ModuleController;


class Controller extends ModuleController {

    public $layout = '/Admin/layouts/main';

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }

    public function redirectWithMessage($url, $message, $time = 4, $status = 404) {
        return $this->show('@root/Admin/prompt', compact('url', 'message', 'time'));
    }
}