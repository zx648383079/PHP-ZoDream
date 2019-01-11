<?php
namespace Module\Forum\Service;

use Module\ModuleController;
use Zodream\Service\Factory;

class Controller extends ModuleController {

    public function findLayoutFile() {
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }
}