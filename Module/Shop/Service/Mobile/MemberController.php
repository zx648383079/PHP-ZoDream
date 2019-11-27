<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Repositories\OrderRepository;

class MemberController extends Controller {

    protected function rules() {
        return [
            'index' => '*',
            'login' => '*',
            '*' => '@'
        ];
    }

    public function indexAction() {
        $user = auth()->user();
        $order_subtotal = OrderRepository::getSubtotal();
        return $this->show(compact('user', 'order_subtotal'));
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