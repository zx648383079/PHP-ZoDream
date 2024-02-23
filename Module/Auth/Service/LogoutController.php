<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Repositories\AuthRepository;

class LogoutController extends Controller {

    public function indexAction(string $redirect_uri = '') {
        AuthRepository::logout(true);
        return $this->redirect(empty($redirect_uri) ? '/' : $redirect_uri);
    }
}