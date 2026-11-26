<?php
declare(strict_types=1);
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Repositories\ShopRepository;

class CategoryController extends Controller {

    public function indexAction() {
        $cat_list = CategoryModel::where('parent_id', 0)->get();
        $site = ShopRepository::siteInfo();
        return $this->show(compact('cat_list', 'site'));
    }

    public function childrenAction(int $id) {
        $this->layout = '';
        $category = CategoryModel::find($id);
        $hot_list = GoodsModel::whereIn('cat_id', $category->getFamily())->where('is_hot', 1)->take(4)->get();
        $category_tree = CategoryModel::getChildrenItem($id);
        return $this->show(compact('hot_list', 'category', 'category_tree'));
    }
}