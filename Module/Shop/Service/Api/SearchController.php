<?php
namespace Module\Shop\Service\Api;

class SearchController extends Controller {

    public function indexAction() {
        return $this->render('api version v1');
    }

    public function keywordsAction() {
        return $this->render([
            '女装', '童装', '玩具'
        ]);
    }
}