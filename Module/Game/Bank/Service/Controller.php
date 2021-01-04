<?php
namespace Module\Game\Bank\Service;

use Module\ModuleController;

class Controller extends ModuleController {

    public $layout = 'main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }
}