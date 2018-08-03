<?php
namespace Module\Auth\Service;

use Module\ModuleController;


class LogoutController extends ModuleController {

    public function indexAction() {
        if (!auth()->guest()) {
            auth()->user()->logout();
        }
        return $this->redirect('/');
    }
}