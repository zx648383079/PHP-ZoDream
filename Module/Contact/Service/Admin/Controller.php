<?php
namespace Module\Contact\Service\Admin;

use Module\ModuleController;

class Controller extends ModuleController {

    public $layout = '/Admin/layouts/main';

    protected function rules() {
        return [
            '*' => 'administrator'
        ];
    }

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }
}