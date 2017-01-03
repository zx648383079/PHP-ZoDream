<?php
namespace Service\Shop;


class HomeController extends Controller {
    public function indexAction() {

        return $this->show([
            'allGoods' => []
        ]);
    }
}