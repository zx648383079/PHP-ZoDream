<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Model\OrderAddressModel;
use Module\Shop\Domain\Model\OrderGoodsModel;
use Module\Shop\Domain\Model\OrderModel;
use Module\Shop\Domain\Model\Scene\Order;

class OrderController extends Controller {

    public function indexAction($id = 0,
                                $status = 0) {
        if ($id > 0) {
            return $this->infoAction($id);
        }
        $order_list = Order::with('goods')
            ->where('user_id', auth()->id())
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', intval($status));
            })
            ->orderBy('created_at', 'desc')
            ->page();
        return $this->renderPage($order_list);
    }

    public function infoAction($id) {
        $order = OrderModel::where('id', $id)->where('user_id', auth()->id())->first();
        $goods_list = OrderGoodsModel::with('goods')->where('order_id', $id)->all();
        $address = OrderAddressModel::where('order_id', $id)->one();
        $data = $order->toArray();
        $data['goods_list'] = $goods_list;
        $data['address'] = $address;
        return $this->render($data);
    }

    public function countAction() {
        return $this->render(OrderModel::getSubtotal());
    }
}