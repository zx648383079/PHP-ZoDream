<?php
namespace Module\CMS\Service\Admin;

class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }
}