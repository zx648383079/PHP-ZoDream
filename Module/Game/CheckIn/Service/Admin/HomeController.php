<?php
namespace Module\Game\CheckIn\Service\Admin;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}