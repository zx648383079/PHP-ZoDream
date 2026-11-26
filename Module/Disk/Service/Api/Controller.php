<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api;

use Module\ModuleController as RestController;

abstract class Controller extends RestController {


    public function rules() {
        return [
          '*' => '@'
        ];
    }
}