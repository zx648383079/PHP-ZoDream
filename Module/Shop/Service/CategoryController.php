<?php
declare(strict_types=1);
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;

class CategoryController extends Controller {

    public function indexAction(int $id) {
        $category = CategoryModel::find($id);
        if (empty($category)) {
            return $this->redirect('./');
        }
        $goods_list = GoodsSimpleModel::take(50)->whereIn('cat_id', $category->getFamily())->get();
        return $this->sendWithShare()->show(compact('category', 'goods_list'));
    }
}