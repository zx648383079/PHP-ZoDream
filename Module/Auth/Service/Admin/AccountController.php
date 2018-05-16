<?php
namespace Module\Auth\Service\Admin;


use Module\Auth\Domain\Model\UserModel;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Routing\Url;

class AccountController extends Controller {

    public function indexAction() {
        $model = Auth::user();
        return $this->show(compact('model'));
    }

    public function passwordAction() {
        return $this->show();
    }


    public function updateAction() {
        /** @var UserModel $model */
        $model = Auth::user();
        $model->name = Request::post('name');
        if ($model->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('user')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function updatePasswordAction() {
        $old_password = Request::post('old_password');
        $password = Request::post('password');
        $confirm_password = Request::post('confirm_password');
        if (empty($password)) {
            return $this->jsonFailure('请输入密码');
        }
        if ($password != $confirm_password) {
            return $this->jsonFailure('两次密码不一致！');
        }
        /** @var UserModel $model */
        $model = Auth::user();
        if (!$model->validatePassword($old_password)) {
            return $this->jsonFailure('密码不正确！');
        }
        $model->setPassword($password);
        if ($model->save()) {
            Auth::user()->logout();
            return $this->jsonSuccess([
                'url' => (string)Url::to('./')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }


}