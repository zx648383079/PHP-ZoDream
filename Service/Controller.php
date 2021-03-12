<?php
declare(strict_types=1);
namespace Service;

use Module\Auth\Domain\Middlewares\RequestMiddleware;
use Zodream\Route\Controller\Controller as BaseController;

abstract class Controller extends BaseController {

    public function __construct()
    {
        $this->middleware(RequestMiddleware::class);
    }
}