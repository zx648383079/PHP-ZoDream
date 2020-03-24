<?php
namespace Module\OpenPlatform\Service\Admin;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}