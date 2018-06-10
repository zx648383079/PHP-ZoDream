<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Model\GoodsModel;

class SearchController extends Controller {

    public function indexAction($keywords = null, $cat_id = 0, $brand_id = 0) {
        $goods_list = GoodsModel::when(!empty($keywords), function ($query) {
            $query->where(function ($query) {
                GoodsModel::search($query, 'name');
            });
        })->when(!empty($cat_id), function ($query) use ($cat_id) {
            $query->where('cat_id', intval($cat_id));
        })->when(!empty($brand_id), function ($query) use ($brand_id) {
            $query->where('brand_id', intval($brand_id));
        })->page();
        return $this->show(compact('goods_list', 'keywords'));
    }
}