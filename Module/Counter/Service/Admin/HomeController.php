<?php
namespace Module\Counter\Service\Admin;

class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }
}