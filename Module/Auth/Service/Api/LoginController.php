<?php
namespace Module\Auth\Service\Api;


use Module\Auth\Domain\Model\UserModel;
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
            return $this->render([
                'token' => auth()->createToken($user),
                'user' => $user->toArray()
            ]);
        }
        return $this->renderFailure($user->getFirstError());
    }
}