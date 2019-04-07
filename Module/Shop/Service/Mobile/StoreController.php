<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\GoodsModel;

class StoreController extends Controller {

    public function indexAction() {
        $new_list = GoodsModel::where('is_new', 1)->limit(8)->all();
        return $this->show(compact('new_list'));
    }
}