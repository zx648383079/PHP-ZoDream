<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Admin;

use Module\Legwork\Domain\Model\OrderModel;

class OrderController extends Controller {

    public function indexAction(int $status = 0) {
        $items = OrderModel::with('service')
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', SORT_DESC)->page();
        $status_list = OrderModel::$status_list;
        return $this->show(compact('items', 'status_list', 'status'));
    }

    public function infoAction(int $id) {
        $order = OrderModel::find($id);
        return $this->show(compact('order'));
    }
}