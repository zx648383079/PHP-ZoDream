<?php
namespace Module\Finance\Service;

use Module\ModuleController;

class Controller extends ModuleController {

    public $layout = 'main';

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

}