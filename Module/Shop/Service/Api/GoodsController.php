<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Model\BrandModel;
use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\GoodsModel;
use Module\Shop\Domain\Model\Scene\Goods;

class GoodsController extends Controller {

    public function indexAction($id = 0,
                                $category = 0,
                                $brand = 0,
                                $keywords = null,
                                $per_page = 20, $sort = null, $order = null) {
        if ($id > 0) {
            return $this->infoAction($id);
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

    public function infoAction($id) {
        $goods = GoodsModel::find($id);
        if (empty($goods)) {
            return $this->renderFailure('商品错误！');
        }
        $data = $goods->toArray();
        $data['properties'] = $goods->properties;
        $data['static_properties'] = $goods->static_properties;
        $data['is_collect'] = $goods->is_collect;
        return $this->render($data);
    }

    public function recommendAction($id) {
        $goods_list = Goods::where('is_best', 1)->limit(3)->all();
        return $this->render($goods_list);
    }

    public function homeAction() {
        $hot_products = Goods::where('is_hot', 1)->all();
        $new_products = Goods::where('is_new', 1)->all();
        $best_products = Goods::where('is_best', 1)->all();
        return $this->render(compact('hot_products', 'new_products', 'best_products'));
    }

    public function countAction() {
        return $this->render([
           'category' => CategoryModel::query()->count(),
           'brand' => BrandModel::query()->count(),
           'goods' => GoodsModel::query()->count()
        ]);
    }
}