<?php
namespace Module\Shop\Service\Mobile;

use Zodream\Domain\Access\Auth;

class MemberController extends Controller {

    public function indexAction() {
        $user = Auth::user();
        return $this->show(compact('user'));
    }

    public function profileAction() {
        $user = Auth::user();
        return $this->show(compact('user'));
    }
}