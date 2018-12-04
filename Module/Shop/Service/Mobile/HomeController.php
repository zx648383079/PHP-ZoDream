<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\Advertisement\AdModel;
use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\GoodsModel;

class HomeController extends Controller {

    public function indexAction() {
        $hot_list = GoodsModel::where('is_hot', 1)->limit(8)->all();
        $new_list = GoodsModel::where('is_new', 1)->limit(8)->all();
        $best_list = GoodsModel::where('is_new', 1)->limit(8)->all();
        $cat_list = CategoryModel::where('parent_id', 0)->limit(10)->all();
        $banners = AdModel::banners();
        return $this->show(compact('hot_list', 'new_list', 'best_list', 'cat_list', 'banners'));
    }
}