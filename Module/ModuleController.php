<?php
declare(strict_types=1);
namespace Module;

use Module\Auth\Domain\Repositories\AuthRepository;
use Service\Controller as BaseController;

abstract class ModuleController extends BaseController {

    protected function checkUser() {
        if (!auth()->guest()) {
            return true;
        }
        if (AuthRepository::loginByBasicAuthorization()) {
            return true;
        }
        return false;
    }
}