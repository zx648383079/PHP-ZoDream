<?php
namespace Module\Demo\Service;

use Module\ModuleController;

class DatetimerController extends ModuleController {
    public function indexAction() {
        return $this->show();
    }
}