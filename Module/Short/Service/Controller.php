<?php
namespace Module\Short\Service;

use Module\ModuleController;

class Controller extends ModuleController {

    public $layout = 'main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }

}