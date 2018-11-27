<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Model\Scene\Goods;

class GoodsController extends Controller {

    public function indexAction() {
        $page = Goods::page();
        return $this->renderPage($page);
    }
}