<?php
namespace Module\Finance;

use Module\ModuleController;

class MoneyController extends ModuleController {

    public function indexAction() {
        return $this->show();
    }
}