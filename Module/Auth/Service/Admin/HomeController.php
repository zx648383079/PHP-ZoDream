<?php
namespace Module\Auth\Service\Admin;


class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}