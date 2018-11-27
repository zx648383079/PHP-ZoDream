<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Model\CategoryModel;

class CategoryController extends Controller {

    public function indexAction($id = 0, $parent = 0) {
        if ($id > 0) {
            return $this->render(CategoryModel::find(intval($id)));
        }
        return $this->render(CategoryModel::where('parent_id', intval($parent))->all());
    }

    public function levelAction() {
        return $this->render(CategoryModel::cacheLevel());
    }

    public function treeAction() {
        return $this->render(CategoryModel::cacheTree());
    }
}