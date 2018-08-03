<?php
namespace Module\Shop\Service\Mobile;



class MemberController extends Controller {

    public function indexAction() {
        $user = auth()->user();
        return $this->show(compact('user'));
    }

    public function profileAction() {
        $user = auth()->user();
        return $this->show(compact('user'));
    }
}