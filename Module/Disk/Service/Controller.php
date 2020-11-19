<?php
namespace Module\Disk\Service;

use Module\ModuleController;

class Controller extends ModuleController {

    public $layout = 'main';

    protected function rules() {
        return [
          '*' => '@'
        ];
    }

    public static function noFound() {
        throw new \ErrorException('404');
    }
}