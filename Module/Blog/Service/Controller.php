<?php
namespace Module\Blog\Service;

use Module\ModuleController;

abstract class Controller extends ModuleController {
    public $layout = true;


    public function findLayoutFile() {
        if ($this->layout === false) {
            return false;
        }
        return app_path('UserInterface/Home/layouts/main.php');
    }
}