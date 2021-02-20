<?php
namespace Module;

use Module\Auth\Domain\Repositories\AuthRepository;
use Zodream\Helpers\Json;
use Zodream\Route\Controller\Controller as BaseController;
use Zodream\Route\Controller\Middleware\RequestMiddleware;

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