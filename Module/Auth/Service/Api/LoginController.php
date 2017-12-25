<?php
namespace Module\Auth\Service\Api;


use Module\Auth\Domain\Model\UserModel;
use Zodream\Domain\Access\JWTAuth;
use Zodream\Route\Controller\RestController;

class LoginController extends RestController {

    protected function rules() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction() {
        $user = new UserModel();
        if ($user->load() && $user->signIn()) {
            return $this->jsonSuccess([
                'token' => JWTAuth::createToken($user)
            ], '登录成功！');
        }
        return $this->jsonFailure($user->getFirstError());
    }
}