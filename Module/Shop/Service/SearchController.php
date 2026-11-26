<?php
declare(strict_types=1);
namespace Module\Shop\Service;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;

class SearchController extends Controller {

    public function indexAction(string $keywords = '') {
        $goods_list = GoodsSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->page();
        return $this->sendWithShare()->show(compact('goods_list', 'keywords'));
    }
}