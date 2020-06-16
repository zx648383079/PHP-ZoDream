<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\GoodsModel;

class SearchController extends Controller {

    public function indexAction() {
        return $this->render('api version v1');
    }

    public function keywordsAction() {
        $data = [];
        return $this->render(compact('data'));
    }

    public function tipsAction($keywords) {
        $data = GoodsModel::when(!empty($keywords), function ($query) {
                GoodsModel::searchWhere($query, 'name');
            })->limit(10)->pluck('name');
        return $this->render(compact('data'));
    }
}