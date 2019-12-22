<?php
namespace Module\Counter\Service\Admin;

class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }

    public function todayAction() {
        $this->layout = false;
        return $this->show();
    }
}