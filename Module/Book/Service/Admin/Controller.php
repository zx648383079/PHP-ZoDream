<?php
namespace Module\Book\Service\Admin;

use Module\ModuleController;
use Zodream\Service\Factory;


class Controller extends ModuleController {

    public $layout = '/Admin/layouts/main';

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    protected function getUrl($path, $args = []) {
        return url('./admin/'.$path, $args);
    }
}