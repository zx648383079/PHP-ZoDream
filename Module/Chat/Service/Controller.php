<?php
namespace Module\Chat\Service;

use Module\ModuleController;

class Controller extends ModuleController {
    protected function rules() {
        return [
            '*' => '@'
        ];
    }
}