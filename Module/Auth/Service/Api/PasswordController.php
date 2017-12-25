<?php
namespace Module\Auth\Service\Api;

use Zodream\Route\Controller\RestController;

class PasswordController extends RestController {

    public function indexAction() {
        return $this->jsonSuccess();
    }

    public function resetAction() {
        return $this->jsonSuccess();
    }
}