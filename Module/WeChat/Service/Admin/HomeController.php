<?php
namespace Module\WeChat\Service\Admin;


class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }
}