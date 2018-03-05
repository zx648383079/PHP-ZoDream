<?php
namespace Module\Shop\Service\Mobile;

class GoodsController extends Controller {

    public function indexAction($id) {
        return $this->show();
    }
}