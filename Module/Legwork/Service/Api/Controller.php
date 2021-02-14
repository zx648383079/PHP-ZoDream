<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api;

use Zodream\Route\Controller\Controller as BaseController;
use Zodream\Route\Controller\Middleware\RequestMiddleware;

abstract class Controller extends BaseController {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function __construct()
    {
        $this->middleware(RequestMiddleware::class);
    }


}