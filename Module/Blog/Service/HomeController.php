<?php
namespace Module\Blog\Service;

use Module\ModuleController;

class HomeController extends ModuleController {

    public function indexAction() {
        return $this->show();
    }

    public function detailAction($id) {
        return $this->show();
    }

    public function updateAction($id, $number) {

    }
}