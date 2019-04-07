<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;

class SearchController extends Controller {

    public function indexAction($keywords = null) {
        $goods_list = GoodsModel::when(!empty($keywords), function ($query) {
            GoodsModel::search($query, 'name');
        })->select(GoodsModel::THUMB_MODE)->page();
        return $this->sendWithShare()->show(compact('goods_list', 'keywords'));
    }
}