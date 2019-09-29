<?php
namespace Module\Auth\Service\Api;


use Module\Auth\Domain\Model\UserModel;
use Zodream\Route\Controller\RestController;

class LoginController extends RestController {

    protected function methods() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction() {
        $model = new UserModel();
        if ($model->load() && $model->signIn()) {
            $user = auth()->user();
            return $this->render(array_merge($user->toArray(), [
                'token' => auth()->createToken($user)
            ]));
        }
        return $this->renderFailure($model->getFirstError());
    }
}