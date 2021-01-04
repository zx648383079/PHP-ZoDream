<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\OrderAddressModel;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\Scene\Order;
use Module\Shop\Domain\Repositories\OrderRepository;


class OrderController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction($status = 0) {
        $order_list = OrderRepository::getList(intval($status));
        if (request()->isAjax()) {
            $this->layout = false;
            return $this->show('page', compact('order_list'));
        }
        $order_subtotal = OrderRepository::getSubtotal();
        return $this->sendWithShare()->show(compact('order_list', 'status', 'order_subtotal'));
    }

    public function detailAction($id) {
        $order = OrderModel::find($id);
        $goods_list = OrderGoodsModel::where('order_id', $id)->all();
        $address = OrderAddressModel::where('order_id', $id)->one();
        return $this->sendWithShare()->show(compact('order', 'goods_list', 'address'));
    }

    public function payAction($id) {
        $order = OrderModel::find($id);
        $order->pay();
    }

    public function receiveAction($id) {
        try {
            OrderRepository::receive($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function cancelAction($id) {
        try {
            OrderRepository::cancel($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function repurchaseAction($id) {
        try {
            OrderRepository::repurchase($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => url('./cart')
        ]);
    }
}