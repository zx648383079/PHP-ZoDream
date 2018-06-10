<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\GoodsModel;

class GoodsController extends Controller {

    public function indexAction($id) {
        $goods = GoodsModel::find($id);
        return $this->show(compact('goods'));
    }
}