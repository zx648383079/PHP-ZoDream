<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;

class CategoryController extends Controller {

    public function indexAction($id) {
        $category = CategoryModel::find($id);
        $goods_list = GoodsModel::limit(50)->whereIn('cat_id', $category->getFamily())->select(GoodsModel::THUMB_MODE)->all();
        return $this->sendWithShare()->show(compact('category', 'goods_list'));
    }
}