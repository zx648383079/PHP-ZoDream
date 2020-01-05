<?php
namespace Module\Counter\Service\Admin;

class CustomController extends Controller {
    public function indexAction() {
        return $this->show();
    }

    public function pageClickAction() {
        return $this->show();
    }
}