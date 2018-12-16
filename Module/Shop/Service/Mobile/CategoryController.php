<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\GoodsModel;

class CategoryController extends Controller {

    public function indexAction() {
        $cat_list = CategoryModel::where('parent_id', 0)->all();
        return $this->show(compact('cat_list'));
    }

    public function childrenAction($id) {
        $this->layout = false;
        $category = CategoryModel::find($id);
        $hot_list = GoodsModel::whereIn('cat_id', $category->getFamily())->where('is_hot', 1)->limit(4)->all();
        $category_tree = CategoryModel::getChildrenItem($id);
        return $this->show(compact('hot_list', 'category', 'category_tree'));
    }
}