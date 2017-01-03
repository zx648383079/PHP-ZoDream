<?php
namespace Service\Shop;


use Domain\Model\Shopping\GoodsModel;

class GoodsController extends Controller {
    public function indexAction($id) {
        $goods = GoodsModel::findOne($id);
        return $this->show([
            'goods' => $goods,
            'recommendGoods' => []
        ]);
    }

    public function commentAction() {
        return $this->show();
    }
}