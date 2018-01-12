<?php
namespace Module\Finance;

use Module\ModuleController;

class IncomeController extends ModuleController {

    public function indexAction() {
        return $this->show();
    }
}