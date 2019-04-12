<?php
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Model\UserModel;
use Zodream\Route\Controller\RestController;

class PasswordController extends RestController {

    protected function rules() {
        return [
            'update' => '@',
        ];
    }

    public function indexAction() {
        return $this->render([
        ]);
    }

    public function resetAction() {
        return $this->render([
        ]);
    }

    public function updateAction($old_password, $password) {
        /** @var UserModel $model */
        $model = auth()->user();
        if (strlen($password) < 6) {
            return $this->renderFailure('密码必须大于6位！');
        }
        if (!$model->validatePassword($old_password)) {
            return $this->renderFailure('密码不正确！');
        }
        $model->setPassword($password);
        if ($model->save()) {
            return $this->render(['data' => true]);
        }
        return $this->renderFailure($model->getFirstError());
    }
}