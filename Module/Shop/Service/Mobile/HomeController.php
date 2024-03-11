<?php
declare(strict_types=1);
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Repositories\ShopRepository;

class HomeController extends Controller {

    public function indexAction() {
        $hot_list = GoodsModel::where('is_hot', 1)->limit(8)->all();
        $new_list = GoodsModel::where('is_new', 1)->limit(8)->all();
        $best_list = GoodsModel::where('is_new', 1)->limit(8)->all();
        $cat_list = CategoryModel::where('parent_id', 0)->limit(10)->all();
        $site = ShopRepository::siteInfo();
        return $this->show(compact('hot_list', 'new_list',
            'best_list', 'cat_list', 'site'));
    }
}