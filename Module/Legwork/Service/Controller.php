<?php
namespace Module\Legwork\Service;

use Module\ModuleController;


abstract class Controller extends ModuleController {

    public $layout = true;

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function findLayoutFile() {
        if ($this->layout === false) {
            return false;
        }
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }
}