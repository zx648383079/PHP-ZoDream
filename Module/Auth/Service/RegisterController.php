<?php
namespace Module\Auth\Service;

use Module\Auth\Domain\Model\UserModel;
use Module\ModuleController;

class RegisterController extends ModuleController {

    protected function rules() {
        return [
            'post' => 'p',
            '*' => '?'
        ];
    }

    public function indexAction() {
        return $this->show(array(
            'title' => '后台注册'
        ));
    }

    public function postAction() {
        $model = new UserModel();
        if ($model->load() && $model->signUp()) {
            return $this->jsonSuccess($model);
        }
        return $this->jsonFailure($model->getError());
    }
}