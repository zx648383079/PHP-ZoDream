<?php
namespace Module\Document\Service;

use Module\ModuleController;

class HomeController extends ModuleController {
    public function indexAction() {
        return $this->show('index');
    }
}