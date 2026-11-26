<?php
declare(strict_types=1);
namespace Module\Shop\Service\Mobile;


use Module\Shop\Domain\Models\OrderGoodsModel;

class RefundController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction(int $status = 0) {
            $goods_list = OrderGoodsModel::where('user_id', auth()->id())
                ->where('after_sale_status', $status)->page();
        return $this->show(compact('goods_list'));
    }

    public function createAction(int $order = 0, int $goods = 0) {
        return $this->show();
    }
}