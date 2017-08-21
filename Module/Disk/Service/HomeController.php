<?php
namespace Module\Disk\Service;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}