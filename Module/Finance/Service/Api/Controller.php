<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Zodream\Route\Controller\RestController;

class Controller extends RestController {
    protected function rules() {
        return ['*' => '@'];
    }
}