<?php
namespace Module\Legwork\Service\Admin;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}