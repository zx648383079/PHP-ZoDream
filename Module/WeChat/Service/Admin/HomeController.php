<?php
namespace Module\WeChat\Service\Admin;

use Module\ModuleController;

class HomeController extends ModuleController {
    public function indexAction() {
        return $this->show();
    }
}