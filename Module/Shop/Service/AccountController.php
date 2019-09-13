<?php
namespace Module\Shop\Service;


use Module\Shop\Domain\Repositories\AccountRepository;

class AccountController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->sendWithShare()->show();
    }

    public function logAction() {
        return $this->sendWithShare()->show();
    }

    public function centerAction() {
        $model_list = AccountRepository::getConnect();
        return $this->sendWithShare()->show(compact('model_list'));
    }
}