<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\GoodsModel;

class SearchController extends Controller {

    public function indexAction() {
        return $this->render('api version v1');
    }

    public function keywordsAction() {
        return $this->render([
            '女装', '童装', '玩具'
        ]);
    }

    public function tipsAction($keywords) {
        $data = GoodsModel::when(!empty($keywords), function ($query) {
                GoodsModel::search($query, 'name');
            })->limit(10)->pluck('name');
        return $this->render($data);
    }
}