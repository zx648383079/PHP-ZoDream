<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\UserModel;
use Module\ModuleController;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Zodream\Service\Factory;
use Zodream\Service\Routing\Url;

class LogoutController extends ModuleController {

    public function indexAction() {
        if (!Auth::guest()) {
            Auth::user()->logout();
        }
        return $this->redirect('/');
    }
}