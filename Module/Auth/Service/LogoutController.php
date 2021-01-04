<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Repositories\AuthRepository;

class LogoutController extends Controller {

    public function indexAction() {
        AuthRepository::logout(true);
        $redirect_uri = request()->get('redirect_uri');
        return $this->redirect(empty($redirect_uri) ? '/' : $redirect_uri);
    }
}