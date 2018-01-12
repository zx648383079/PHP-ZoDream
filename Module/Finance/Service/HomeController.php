<?php
namespace Module\Finance;

use Module\ModuleController;

class HomeController extends ModuleController {

    public function indexAction() {
        return $this->show();
    }
}