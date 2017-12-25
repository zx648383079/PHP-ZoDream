<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Model\UserModel;
use Zodream\Domain\Access\JWTAuth;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;

class RegisterController extends RestController {

    protected function rules() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction() {
        $model = new UserModel();
        if ($model->load(Request::post('email,fullName:name,password,confirmPassword:rePassword,terms:agree'))
            && $model->signUp()) {
            return $this->jsonSuccess([], '登录成功！');
        }
        return $this->jsonFailure($model->getFirstError());
    }
}