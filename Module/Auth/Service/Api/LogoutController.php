<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Repositories\AuthRepository;
use Zodream\Route\Controller\RestController;

class LogoutController extends RestController {

    public function indexAction() {
        AuthRepository::logout();
        return $this->render([]);
    }
}