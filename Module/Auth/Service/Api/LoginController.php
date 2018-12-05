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
        $user = new UserModel();
        if ($user->load() && $user->signIn()) {
            return $this->render(array_merge(auth()->user()->toArray(), [
                'token' => auth()->createToken(auth()->user())
            ]));
        }
        return $this->renderFailure($user->getFirstError());
    }
}