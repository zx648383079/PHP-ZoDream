<?php
declare(strict_types=1);
namespace Module\Chat\Service;

use Module\ModuleController;

class Controller extends ModuleController {
    public function rules() {
        return [
            '*' => '@'
        ];
    }
}