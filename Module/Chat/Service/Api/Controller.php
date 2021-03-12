<?php
declare(strict_types=1);
namespace Module\Chat\Service\Api;

use Module\ModuleController as BaseController;
use Zodream\Route\Controller\Middleware\RequestMiddleware;

abstract class Controller extends BaseController {

    public function rules() {
        return [
            '*' => '@'
        ];
    }


}