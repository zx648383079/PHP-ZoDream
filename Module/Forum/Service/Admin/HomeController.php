<?php
namespace Module\Forum\Service\Admin;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}