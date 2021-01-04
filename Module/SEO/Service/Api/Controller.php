<?php
namespace Module\SEO\Service\Api;

use Module\Auth\Domain\Concerns\AdminRole;
use Zodream\Route\Controller\Controller as RestController;
use Zodream\Route\Controller\Middleware\RequestMiddleware;

class Controller extends RestController {

    use AdminRole;

    public function __construct()
    {
        $this->middleware(RequestMiddleware::class);
    }
}