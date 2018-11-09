<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Model\UserModel;
use Zodream\Domain\Access\JWTAuth;

use Zodream\Route\Controller\RestController;

class RegisterController extends RestController {

    protected function rules() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction() {
        $model = new UserModel();
        if ($model->load(app('request')
                ->get('email,fullName:name,password,confirmPassword:rePassword,terms:agree'))
            && $model->signUp()) {
            $user = auth()->user();
            return $this->render([
                'token' => auth()->createToken($user),
                'user' => $user->toArray()
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }
}