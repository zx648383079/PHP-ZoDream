<?php
namespace Module\Blog\Service\Admin;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}