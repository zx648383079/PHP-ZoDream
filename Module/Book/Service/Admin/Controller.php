<?php
namespace Module\Book\Service\Admin;

use Module\ModuleController;
use Zodream\Service\Factory;
use Zodream\Service\Routing\Url;

class Controller extends ModuleController {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    protected function getUrl($path, $args = []) {
        return (string)Url::to('./admin/'.$path, $args);
    }
}