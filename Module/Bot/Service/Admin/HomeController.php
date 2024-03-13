<?php
namespace Module\Bot\Service\Admin;

class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }
}