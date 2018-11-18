<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Model\GoodsModel;

class CartController extends Controller {

    public function indexAction() {
        $like_goods = GoodsModel::limit(7)->select(GoodsModel::THUMB_MODE)->all();
        return $this->sendWithShare()->show(compact('like_goods'));
    }

    public function checkoutAction() {
        return $this->show();
    }
}