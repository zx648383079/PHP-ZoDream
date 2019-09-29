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
        if ($model->load()
            && $model->signUp()) {
            $user = auth()->user();
            return $this->render(array_merge($user->toArray(), [
                'token' => auth()->createToken($user)
            ]));
        }
        return $this->renderFailure($model->getFirstError());
    }
}