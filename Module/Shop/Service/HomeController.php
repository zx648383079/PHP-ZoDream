<?php
namespace Module\Shop\Service;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}