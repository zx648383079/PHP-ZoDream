<?php
namespace Module\Auth\Service;

use Module\ModuleController;

class PasswordController extends ModuleController {

    public function indexAction() {
        return $this->show();
    }

    public function findAction() {
        return $this->show([
            'title' => '找回密码'
        ]);
    }
}