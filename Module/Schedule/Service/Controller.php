<?php
namespace Module\Schedule\Service;

use Module\ModuleController;

abstract class Controller extends ModuleController {
    public function rules() {
        return [
            '*' => 'cli'
        ];
    }
}