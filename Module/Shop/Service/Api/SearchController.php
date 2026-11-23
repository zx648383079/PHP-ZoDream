<?php
namespace Module\Shop\Service\Api;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\GoodsModel;

class SearchController extends Controller {

    public function indexAction() {
        return $this->render('api version v1');
    }

    public function keywordsAction() {
        $data = GoodsModel::query()->take(5)->pluck('name');
        return $this->render(compact('data'));
    }

    public function tipsAction(string $keywords) {
        $data = GoodsModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
            })->take(10)->pluck('name');
        return $this->render(compact('data'));
    }
}