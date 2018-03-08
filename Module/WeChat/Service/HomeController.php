<?php
namespace Module\WeChat\Service;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}