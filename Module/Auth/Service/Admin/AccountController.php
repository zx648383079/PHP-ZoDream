<?php
namespace Module\Auth\Service\Admin;


use Module\Auth\Domain\Model\UserModel;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\Http\URL;

class AccountController extends Controller {

    public function indexAction() {
        $model = auth()->user();
        return $this->show(compact('model'));
    }

    public function passwordAction() {
        return $this->show();
    }


    public function updateAction() {
        /** @var UserModel $model */
        $model = auth()->user();
        $model->name = app('request')->get('name');
        if ($model->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('user')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function updatePasswordAction() {
        $old_password = app('request')->get('old_password');
        $password = app('request')->get('password');
        $confirm_password = app('request')->get('confirm_password');
        if (empty($password)) {
            return $this->jsonFailure('请输入密码');
        }
        if ($password != $confirm_password) {
            return $this->jsonFailure('两次密码不一致！');
        }
        /** @var UserModel $model */
        $model = auth()->user();
        if (!$model->validatePassword($old_password)) {
            return $this->jsonFailure('密码不正确！');
        }
        $model->setPassword($password);
        if ($model->save()) {
            auth()->user()->logout();
            return $this->jsonSuccess([
                'url' => (string)URL::to('./')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }


}