<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Model\Scene\Goods;

class GoodsController extends Controller {

    public function indexAction($per_page = 20) {
        $page = Goods::page($per_page);
        return $this->renderPage($page);
    }

    public function homeAction() {
        $hot_products = Goods::where('is_hot', 1)->all();
        $new_products = Goods::where('is_new', 1)->all();
        $best_products = Goods::where('is_best', 1)->all();
        return $this->render(compact('hot_products', 'new_products', 'best_products'));
    }
}