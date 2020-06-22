<?php
namespace Module\Demo\Service;

use Module\ModuleController;
use Zodream\Service\Factory;

class Controller extends ModuleController {

    public $layout = true;


    public function findLayoutFile() {
        if ($this->layout === false) {
            return false;
        }
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }
}