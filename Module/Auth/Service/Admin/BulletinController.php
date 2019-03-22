<?php
namespace Module\Auth\Service\Admin;


class BulletinController extends Controller {

    public function indexAction() {
        return $this->show();
    }

    public function createAction() {
        return $this->show();
    }
}