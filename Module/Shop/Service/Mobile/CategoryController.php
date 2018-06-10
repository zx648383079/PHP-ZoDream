<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\CategoryModel;

class CategoryController extends Controller {

    public function indexAction() {
        $cat_list = CategoryModel::where('parent_id', 0)->all();
        return $this->show(compact('cat_list'));
    }
}