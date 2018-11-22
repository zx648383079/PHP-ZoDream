<?php
namespace Module\Shop\Service\Mobile;



class MemberController extends Controller {

    public function indexAction() {
        $user = auth()->user();
        return $this->show(compact('user'));
    }


    public function loginAction() {
        return $this->show();
    }

    public function profileAction() {
        $user = auth()->user();
        return $this->show(compact('user'));
    }
}