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

    public function sendFindEmailAction($email) {
        if (empty($email)) {
            return $this->renderFailure('请输入有效邮箱');
        }
        $user = UserModel::findByEmail($email);
        if (empty($user)) {
            return $this->renderFailure('邮箱未注册');
        }
        return $this->render([
            'data' => true,
            'message' => sprintf('邮件已成功发送至 %s 请注意查收！', $email)
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