<?php
namespace Module\Shop\Service\Mobile;


use Module\Shop\Domain\Models\OrderGoodsModel;

class RefundController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction($status = 0) {
            $goods_list = OrderGoodsModel::where('user_id', auth()->id())
                ->where('after_sale_status', $status)->page();
        return $this->show(compact('goods_list'));
    }

    public function createAction($order = 0, $goods = 0) {
        return $this->show();
    }
}