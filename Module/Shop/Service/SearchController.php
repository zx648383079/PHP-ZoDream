<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\GoodsModel;

class SearchController extends Controller {

    public function indexAction($keywords = null) {
        $goods_list = GoodsModel::when(!empty($keywords), function ($query) {
            GoodsModel::search($query, 'name');
        })->limit(7)->select(GoodsModel::THUMB_MODE)->all();
        return $this->sendWithShare()->show(compact('goods_list', 'keywords'));
    }
}