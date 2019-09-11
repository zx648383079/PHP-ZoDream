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
        return $this->show();
    }

    public function postAction() {
        $model = new UserModel();
        if ($model->load() && $model->signUp()) {
            $model->logLogin();
            return $this->jsonSuccess([
                'url' => url('/')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }
}