<?php
namespace Module\Template\Service;

use Module\ModuleController;

class HomeController extends ModuleController {

    public function indexAction() {

        return $this->show();
    }


}