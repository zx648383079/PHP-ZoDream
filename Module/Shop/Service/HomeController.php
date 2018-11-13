<?php
namespace Module\Shop\Service;


use Module\Shop\Domain\Model\GoodsModel;

class HomeController extends Controller {

    public function indexAction() {
        $new_goods = GoodsModel::limit(7)->select('id', 'name', 'thumb', 'price')->all();
        $best_goods = $new_goods;
        return $this->sendWithShare()->show(compact('new_goods', 'best_goods'));
    }
}