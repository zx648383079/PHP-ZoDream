<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Model\UserModel;
use Zodream\Domain\Access\JWTAuth;
use Zodream\Route\Controller\RestController;

class RegisterController extends RestController {

    public function indexAction() {
        $model = new UserModel();
        if ($model->load() && $model->signUp()) {
            return $this->jsonSuccess([
                'token' => JWTAuth::getToken($model)
            ], '登录成功！');
        }
        return $this->jsonFailure($model->getError());
    }
}