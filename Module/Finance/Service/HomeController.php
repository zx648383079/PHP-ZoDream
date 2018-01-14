<?php
namespace Module\Finance\Service;

use Module\ModuleController;

class HomeController extends ModuleController {

    public function indexAction() {
        return $this->show();
    }
}