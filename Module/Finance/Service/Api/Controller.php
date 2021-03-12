<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\ModuleController as RestController;
use Zodream\Route\Controller\Middleware\RequestMiddleware;

class Controller extends RestController {

    public function rules() {
        return ['*' => '@'];
    }
}