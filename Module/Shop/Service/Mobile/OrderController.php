<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\OrderAddressModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderLogModel;
use Module\Shop\Domain\Models\OrderModel;


class OrderController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction($status = 0) {
        $order_list = OrderModel::with('goods')
            ->where('user_id', auth()->id())
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', intval($status));
            })
            ->orderBy('created_at', 'desc')
            ->page();
        return $this->show(compact('order_list', 'status'));
    }

    public function detailAction($id) {
        $order = OrderModel::where('id', $id)->where('user_id', auth()->id())->first();
        $goods_list = OrderGoodsModel::where('order_id', $id)->all();
        $address = OrderAddressModel::where('order_id', $id)->one();
        return $this->show(compact('order', 'goods_list', 'address'));
    }

    public function receiveAction($id) {
        $order = OrderModel::findWithAuth($id);
        if (!$order || $order->status != OrderModel::STATUS_SHIPPED) {
            return $this->jsonFailure('签收失败！');
        }
        if (!OrderLogModel::receive($order)) {
            return $this->jsonFailure('签收失败！');
        }
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function cancelAction($id) {
        $order = OrderModel::findWithAuth($id);
        if (!$order || !in_array($order->status, [OrderModel::STATUS_UN_PAY, OrderModel::STATUS_PAID_UN_SHIP])) {
            return $this->jsonFailure('取消失败！');
        }
        if (!OrderLogModel::cancel($order)) {
            return $this->jsonFailure('取消失败！');
        }
        return $this->jsonSuccess([
           'refresh' => true
        ]);
    }
}