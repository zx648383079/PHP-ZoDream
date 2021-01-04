<?php
namespace Module\Family\Service;

use Module\ModuleController;


class Controller extends ModuleController {
    public $layout = true;


    public function findLayoutFile() {
        if ($this->layout === false) {
            return false;
        }
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }
}