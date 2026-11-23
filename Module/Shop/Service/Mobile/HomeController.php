<?php
declare(strict_types=1);
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Repositories\ShopRepository;

class HomeController extends Controller {

    public function indexAction() {
        $hot_list = GoodsModel::where('is_hot', 1)->take(8)->get();
        $new_list = GoodsModel::where('is_new', 1)->take(8)->get();
        $best_list = GoodsModel::where('is_new', 1)->take(8)->get();
        $cat_list = CategoryModel::where('parent_id', 0)->take(10)->get();
        $site = ShopRepository::siteInfo();
        return $this->show(compact('hot_list', 'new_list',
            'best_list', 'cat_list', 'site'));
    }
}