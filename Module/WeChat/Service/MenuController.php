<?php
namespace Module\WeChat\Service;

use Module\ModuleController;

class MenuController extends ModuleController {

    public function indexAction() {
        return $this->show();
    }
}