<?php
namespace Module;

use Module\Auth\Domain\Model\UserModel;
use Zodream\Domain\Access\Auth;
use Zodream\Service\Controller\ModuleController as BaseController;

abstract class ModuleController extends BaseController {

    public function getConfig() {
        return [];
    }

    public function setConfig($data) {

    }

    protected function checkUser() {
        if (!Auth::guest()) {
            return true;
        }
        $user = new UserModel();
        if ($user->signInHeader()) {
            return true;
        }
        return false;
    }


}