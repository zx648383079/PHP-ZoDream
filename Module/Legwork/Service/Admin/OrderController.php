<?php
namespace Module\Legwork\Service\Admin;

use Module\Legwork\Domain\Model\OrderModel;

class OrderController extends Controller {

    public function indexAction($status = 0) {
        $items = OrderModel::with('service')
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')->page();
        $status_list = OrderModel::$status_list;
        return $this->show(compact('items', 'status_list', 'status'));
    }

    public function infoAction($id) {
        $order = OrderModel::find($id);
        return $this->show(compact('order'));
    }
}