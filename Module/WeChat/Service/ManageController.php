<?php
namespace Module\WeChat\Service;

use Module\ModuleController;

class ManageController extends ModuleController {

    public function indexAction() {
        return $this->show();
    }
}