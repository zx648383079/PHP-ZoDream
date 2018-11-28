<?php
namespace Module\Shop\Service\Mobile;



class MemberController extends Controller {

    public function indexAction() {
        $user = auth()->user();
        return $this->show(compact('user'));
    }


    public function loginAction() {
        $redirect_uri = app('request')->get('redirect_uri');
        if (empty($redirect_uri)) {
            $redirect_uri = url('./mobile');
        }
        return $this->show(compact('redirect_uri'));
    }

    public function profileAction() {
        $user = auth()->user();
        return $this->show(compact('user'));
    }
}