<?php
namespace Module\Legwork\Service;

use Module\ModuleController;
use Zodream\Service\Factory;

abstract class Controller extends ModuleController {

    public $layout = true;

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function findLayoutFile() {
        if ($this->layout === false) {
            return false;
        }
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }
}