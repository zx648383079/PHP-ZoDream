<?php
namespace Module\Shop\Service;

class MemberController extends Controller {

    public function indexAction() {
        $user = auth()->user();
        return $this->show(compact('user'));
    }

    public function loginAction() {
        $redirect_uri = app('request')->get('redirect_uri');
        if (empty($redirect_uri)) {
            $redirect_uri = url('./');
        }
        return $this->sendWithShare()->show(compact('redirect_uri'));
    }

    public function profileAction() {
        return $this->sendWithShare()->show();
    }

    public function historyAction() {
        return $this->sendWithShare()->show();
    }
}