<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\GoodsModel;

class GoodsController extends Controller {

    public function indexAction($id) {
        $goods = GoodsModel::find($id);
        $goods_list = GoodsModel::limit(4)->all();
        $comment_list = [];
        return $this->show(compact('goods', 'goods_list', 'comment_list'));
    }

    public function priceAction($id, $amount = 1) {
        $goods = GoodsModel::find($id);
        $price = $goods->getPrice($amount);
        return $this->jsonSuccess($price);
    }
}