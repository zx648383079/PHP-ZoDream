<?php
namespace Module\Auth\Service;

use Module\ModuleController;


class LogoutController extends ModuleController {

    public function indexAction() {
        if (!auth()->guest()) {
            auth()->user()->logout();
        }
        $redirect_uri = app('request')->get('redirect_uri');
        return $this->redirect(empty($redirect_uri) ? '/' : $redirect_uri);
    }
}