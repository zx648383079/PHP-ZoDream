<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\GoodsModel;

class HomeController extends Controller {

    public function indexAction() {
        $hot_list = GoodsModel::where('is_hot', 0)->limit(8)->all();
        $cat_list = CategoryModel::where('parent_id', 0)->limit(10)->all();
        return $this->show(compact('hot_list', 'cat_list'));
    }
}