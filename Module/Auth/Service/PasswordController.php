<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Model\UserModel;
use Module\ModuleController;

class PasswordController extends ModuleController {

    public function indexAction($code) {
        if (empty($code)) {
            return $this->redirectWithMessage('/', '密码找回失败');
        }
        return $this->show();
    }

    public function findAction() {
        return $this->show();
    }

    public function resetAction() {
        $model = new UserModel();
        if ($model->load() && $model->findPassword()) {
            return $this->jsonSuccess([
                'url' => url('./')
            ]);
        }
        return $this->jsonFailure($model->getError());
    }

    public function sendAction($email) {
        if (empty($email)) {
            return $this->jsonFailure('请输入有效邮箱');
        }
        $user = UserModel::findByEmail($email);
        if (empty($user)) {
            return $this->jsonFailure('邮箱未注册');
        }
        return $this->jsonSuccess(null, sprintf('邮件已成功发送至 %s 请注意查收！', $email));
    }


}