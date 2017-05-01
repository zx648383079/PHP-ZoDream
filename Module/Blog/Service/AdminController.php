<?php
namespace Module\Blog\Service;

use Module\ModuleController;

class AdminController extends ModuleController {

    public function indexAction() {
        return $this->show();
    }
}