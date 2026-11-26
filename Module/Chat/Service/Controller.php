<?php
declare(strict_types=1);
namespace Module\Chat\Service;

use Module\ModuleController;

abstract class Controller extends ModuleController {
    public function rules() {
        return [
            '*' => '@'
        ];
    }
}