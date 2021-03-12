<?php
namespace Module\SEO\Service\Api;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController as RestController;
use Zodream\Route\Controller\Middleware\RequestMiddleware;

class Controller extends RestController {

    use AdminRole;

}