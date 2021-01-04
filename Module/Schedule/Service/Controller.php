<?php
namespace Module\Schedule\Service;

use Module\ModuleController;

class Controller extends ModuleController {
    public function rules() {
        return [
            '*' => 'cli'
        ];
    }
}