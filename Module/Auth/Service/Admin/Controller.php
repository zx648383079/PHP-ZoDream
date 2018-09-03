<?php
namespace Module\Auth\Service\Admin;

use Module\ModuleController;


class Controller extends ModuleController {

    public $layout = '/Admin/layouts/main';

    public function prepare() {
        if (app('request')->isPjax()) {
            $this->layout = '/Admin/layouts/x_main';
        }
    }



    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    protected function getUrl($path, $args = []) {
        return url('./admin/'.$path, $args);
    }

    public function redirectWithMessage($url, $message, $time = 4, $status = 404) {
        return $this->show('/admin/prompt', compact('url', 'message', 'time'));
    }
}