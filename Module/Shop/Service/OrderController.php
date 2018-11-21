<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Model\OrderGoodsModel;
use Module\Shop\Domain\Model\OrderModel;


class OrderController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $order_list = OrderModel::with('goods')
            ->where('user_id', auth()->id())
            ->page();
        return $this->sendWithShare()->show(compact('order_list'));
    }

    public function detailAction($id) {
        $order = OrderModel::find($id);
        $goods_list = OrderGoodsModel::where('order_id', $id)->all();
        return $this->sendWithShare()->show(compact('order', 'goods_list'));
    }

    public function payAction($id) {
        $order = OrderModel::find($id);
        $order->pay();
    }
}