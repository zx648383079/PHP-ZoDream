<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Model\GoodsModel;
use Module\Shop\Domain\Model\Scene\Goods;

class GoodsController extends Controller {

    public function indexAction($id = 0,
                                $category = 0,
                                $brand = 0,
                                $keywords = null,
                                $per_page = 20, $sort = null, $order = null) {
        if ($id > 0) {
            return $this->render(GoodsModel::find($id));
        }
        $page = Goods::sortBy($sort, $order)
            ->when(!empty($keywords), function ($query) {
                GoodsModel::search($query, 'name');
            })->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', intval($category));
            })->when($brand > 0, function ($query) use ($brand) {
                $query->where('brand_id', intval($brand));
            })->page($per_page);
        return $this->renderPage($page);
    }

    public function homeAction() {
        $hot_products = Goods::where('is_hot', 1)->all();
        $new_products = Goods::where('is_new', 1)->all();
        $best_products = Goods::where('is_best', 1)->all();
        return $this->render(compact('hot_products', 'new_products', 'best_products'));
    }
}