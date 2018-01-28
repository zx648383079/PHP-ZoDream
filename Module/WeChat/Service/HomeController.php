<?php
namespace Module\WeChat\Service;

use Module\ModuleController;

class HomeController extends ModuleController {
    public function indexAction() {
        return $this->show();
    }
}