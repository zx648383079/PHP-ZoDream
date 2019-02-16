<?php
namespace Module\MicroBlog\Service\Admin;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}