<?php
namespace Module\Code\Service\Admin;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}