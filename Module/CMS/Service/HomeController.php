<?php
namespace Module\CMS\Service;

class HomeController extends Controller {

    public function indexAction() {

        return $this->show();
    }
}