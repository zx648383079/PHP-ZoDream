<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Model\CategoryModel;
use Module\Shop\Domain\Model\GoodsModel;

class SearchController extends Controller {

    public function indexAction($keywords) {
        $goods_list = GoodsModel::limit(7)->select(GoodsModel::THUMB_MODE)->all();
        return $this->sendWithShare()->show(compact('goods_list', 'keywords'));
    }
}