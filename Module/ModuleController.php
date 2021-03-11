<?php
namespace Module;

use Module\Auth\Domain\Middlewares\RequestMiddleware;
use Module\Auth\Domain\Repositories\AuthRepository;
use Zodream\Route\Controller\Controller as BaseController;

abstract class ModuleController extends BaseController {

    public function __construct()
    {
        $this->middleware(RequestMiddleware::class);
    }

    public function getConfig() {
        return [];
    }

    public function setConfig($data) {

    }

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