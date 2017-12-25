<?php
namespace Module\Auth\Service;

use Module\ModuleController;
use Zodream\Domain\Access\Auth;

class LogoutController extends ModuleController {

    public function indexAction() {
        if (!Auth::guest()) {
            Auth::user()->logout();
        }
        return $this->redirect('/');
    }
}