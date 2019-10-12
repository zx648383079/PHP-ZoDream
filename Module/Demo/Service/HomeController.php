<?php
namespace Module\Demo\Service;

use Module\ModuleController;
use Zodream\Service\Factory;

class HomeController extends ModuleController {

    public function indexAction() {
        return $this->show();
    }

    public function findLayoutFile() {
        if ($this->layout === false) {
            return false;
        }
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }
}