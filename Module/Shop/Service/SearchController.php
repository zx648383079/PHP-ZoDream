<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;

class SearchController extends Controller {

    public function indexAction($keywords = null) {
        $goods_list = GoodsSimpleModel::when(!empty($keywords), function ($query) {
            GoodsModel::searchWhere($query, 'name');
        })->page();
        return $this->sendWithShare()->show(compact('goods_list', 'keywords'));
    }
}