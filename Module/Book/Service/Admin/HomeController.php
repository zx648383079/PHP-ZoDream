<?php
namespace Module\Book\Service\Admin;


class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }
}