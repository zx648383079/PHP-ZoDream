<?php
namespace Module\Chat\Service;

use Module\ModuleController;

class Controller extends ModuleController {
    public function rules() {
        return [
            '*' => '@'
        ];
    }
}