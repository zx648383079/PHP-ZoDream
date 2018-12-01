<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\GoodsModel;

class CategoryController extends Controller {

    public function indexAction($id) {
        $category = CategoryModel::find($id);
        $goods_list = GoodsModel::limit(50)->whereIn('cat_id', $category->getFamily())->select(GoodsModel::THUMB_MODE)->all();
        return $this->sendWithShare()->show(compact('category', 'goods_list'));
    }
}