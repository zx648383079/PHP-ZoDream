<?php
namespace Module\Auth\Service\Api;

use Zodream\Domain\Access\JWTAuth;
use Zodream\Route\Controller\RestController;

class LogoutController extends RestController {

    public function indexAction() {
        auth()->logout();
        return $this->render([
        ]);
    }
}