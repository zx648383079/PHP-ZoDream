<?php
namespace Module\Game\CheckIn\Service;

use Module\ModuleController;

class Controller extends ModuleController {

    public $layout = 'main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }
}