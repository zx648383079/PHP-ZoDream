<?php
declare(strict_types=1);
namespace Module\Chat\Service\Api;

use Zodream\Route\Controller\Controller as BaseController;
use Zodream\Route\Controller\Middleware\RequestMiddleware;

abstract class Controller extends BaseController {

    public function __construct()
    {
        $this->middleware(RequestMiddleware::class);
    }

    public function rules() {
        return [
            '*' => '@'
        ];
    }


}