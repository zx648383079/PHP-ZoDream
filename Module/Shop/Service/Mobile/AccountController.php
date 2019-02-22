<?php
namespace Module\Shop\Service\Mobile;


class AccountController extends Controller {

    protected function rules() {
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
        return $this->show();
    }
}