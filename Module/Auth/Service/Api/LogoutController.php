<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Repositories\AuthRepository;

class LogoutController extends Controller {
    
    public function methods() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction() {
        AuthRepository::logout();
        return $this->renderData(true);
    }
}