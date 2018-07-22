<?php
namespace Module\Book\Service\Admin;

use Module\ModuleController;
use Zodream\Service\Factory;
use Zodream\Infrastructure\Http\URL;

class Controller extends ModuleController {

    public $layout = '/Admin/layouts/main';

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    protected function getUrl($path, $args = []) {
        return (string)URL::to('./admin/'.$path, $args);
    }
}