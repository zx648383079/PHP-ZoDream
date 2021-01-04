<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Repositories\OrderRepository;

class MemberController extends Controller {

    public function rules() {
        return [
            'login' => '?',
            '*' => '@'
        ];
    }

    public function indexAction() {
        $user = auth()->user();
        $order_list = OrderRepository::getList([OrderModel::STATUS_UN_PAY, OrderModel::STATUS_PAID_UN_SHIP, OrderModel::STATUS_SHIPPED]);
        return $this->show(compact('user', 'order_list'));
    }

    public function loginAction() {
        $redirect_uri = request()->get('redirect_uri');
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