<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Model\UserModel;
use Zodream\Route\Controller\RestController;

class UserController extends RestController {

    protected function rules() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction() {

    }


    public function checkAction($email) {
        $user = UserModel::findByEmail($email);
        if (empty($user)) {
            return $this->renderFailure('email error');
        }
        return $this->render([
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar
        ]);
    }

}