<?php
declare(strict_types=1);
namespace Module\Forum\Service\Api;

use Zodream\Route\Controller\Controller as RestController;
use Zodream\Route\Controller\Middleware\RequestMiddleware;

class Controller extends RestController {

    public function __construct()
    {
        $this->middleware(RequestMiddleware::class);
    }
}