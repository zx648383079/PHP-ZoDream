<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\OrderModel;

class OrderController extends Controller {

    public function indexAction() {
        $order_list = OrderModel::all();
        return $this->show(compact('order_list'));
    }

    public function detailAction($id) {
        $order = OrderModel::find($id);
        return $this->show(compact('order'));
    }

    public function payAction($id) {
        $order = OrderModel::find($id);
        $order->pay();
    }
}