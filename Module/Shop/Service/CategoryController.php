<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;

class CategoryController extends Controller {

    public function indexAction($id) {
        $category = CategoryModel::find(intval($id));
        if (empty($category)) {
            return $this->redirect('./');
        }
        $goods_list = GoodsSimpleModel::limit(50)->whereIn('cat_id', $category->getFamily())->all();
        return $this->sendWithShare()->show(compact('category', 'goods_list'));
    }
}