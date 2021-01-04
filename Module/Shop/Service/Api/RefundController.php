<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderModel;

class RefundController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction($status = 0) {
        $goods_list = OrderGoodsModel::where('user_id', auth()->id())
            ->whereIn('status', [OrderModel::STATUS_PAID_UN_SHIP,
                OrderModel::STATUS_SHIPPED, OrderModel::STATUS_RECEIVED, OrderModel::STATUS_FINISH])
            ->where('after_sale_status', $status)->orderBy('id', 'desc')->page();
        return $this->renderPage($goods_list);
    }

    public function goodsAction($goods = 0, $order = 0) {
        if (empty($goods) && empty($order)) {
            return $this->renderFailure('请选择售后的商品');
        }
        $goods_list = OrderGoodsModel::where('user_id', auth()->id())
            ->when(!empty($goods), function ($query) use ($goods) {
                $query->where('id', $goods);
            }, function ($query) use ($order) {
                $query->where('order_id', $order);
            })
            ->whereIn('status', [OrderModel::STATUS_PAID_UN_SHIP,
                OrderModel::STATUS_SHIPPED, OrderModel::STATUS_RECEIVED, OrderModel::STATUS_FINISH])
            ->where('after_sale_status', 0)->get();
        if (empty($goods_list)) {
            return $this->renderFailure('商品不存在');
        }
        return $this->render($goods_list);
    }

}