<?php
namespace Module\Note\Service\Admin;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}