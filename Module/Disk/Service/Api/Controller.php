<?php
namespace Module\Disk\Service\Api;

use Zodream\Route\Controller\RestController;

class Controller extends RestController {


    protected function rules() {
        return [
          '*' => '@'
        ];
    }
}