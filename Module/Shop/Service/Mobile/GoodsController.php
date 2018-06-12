<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\GoodsModel;

class GoodsController extends Controller {

    public function indexAction($id) {
        $goods = GoodsModel::find($id);
        return $this->show(compact('goods'));
    }

    public function priceAction($id, $amount = 1) {
        $goods = GoodsModel::find($id);
        $price = $goods->getPrice($amount);
        return $this->jsonSuccess($price);
    }
}