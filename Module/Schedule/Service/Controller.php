<?php
namespace Module\Schedule\Service;

use Module\ModuleController;

class Controller extends ModuleController {
    protected function rules() {
        return [
            '*' => 'cli'
        ];
    }
}