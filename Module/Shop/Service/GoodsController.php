<?php
namespace Module\Shop\Service;

class GoodsController extends Controller {

    public function indexAction($id) {
        return $this->show();
    }
}