<?php
namespace Module\Shop\Service\Mobile;


use Module\Auth\Domain\Repositories\AccountRepository;

class AccountController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->show();
    }

    public function logAction() {
        return $this->show();
    }

    public function centerAction() {
        $model_list = AccountRepository::getConnect();
        return $this->show(compact('model_list'));
    }
}