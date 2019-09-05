<?php
namespace Module\Game\TCG\Service;

use Module\ModuleController;

class Controller extends ModuleController {

    public $layout = 'main';

    protected function rules() {
        return [
            '*' => '@'
        ];
    }
}