<?php
namespace Module\Shop\Service\Admin;

use Module\Shop\Domain\Model\OrderModel;
use Module\Shop\Domain\Model\OrderAddressModel;
use Module\Shop\Domain\Model\OrderGoodsModel;
use Module\Auth\Domain\Model\UserModel;

class OrderController extends Controller {

    public function indexAction() {
        $model_list = OrderModel::with('user')
            ->orderBy('created_at', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function infoAction($id) {
        $order = OrderModel::find($id);
        $goods_list = OrderGoodsModel::where('order_id', $id)->all();
        $address = OrderAddressModel::where('order_id', $id)->one();
        $user = UserModel::find($order->user_id);
        return $this->show(compact('order', 'goods_list', 'address', 'user'));
    }
}