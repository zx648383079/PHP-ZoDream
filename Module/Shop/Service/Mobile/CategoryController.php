<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\GoodsModel;

class CategoryController extends Controller {

    public function indexAction() {
        $cat_list = CategoryModel::where('parent_id', 0)->all();
        $hot_list = GoodsModel::limit(8)->all();
        return $this->show(compact('cat_list', 'hot_list'));
    }
}