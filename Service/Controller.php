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

    public function redirectWithMessage(mixed $url, string $message, int $time = 4, int $status = 404) {
        return $this->show('@root/Home/404', compact('url', 'message', 'time'));
    }
}