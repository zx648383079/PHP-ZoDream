<?php
namespace Module\Shop\Service;

class MemberController extends Controller {

    public function indexAction() {
        return $this->show();
    }

    public function loginAction() {
        return $this->sendWithShare()->show();
    }
}