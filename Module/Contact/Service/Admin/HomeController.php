<?php
namespace Module\Contact\Service\Admin;

class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }

}