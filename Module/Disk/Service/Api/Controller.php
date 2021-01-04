<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api;

use Zodream\Route\Controller\Controller as RestController;
use Zodream\Route\Controller\Middleware\RequestMiddleware;

class Controller extends RestController {

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