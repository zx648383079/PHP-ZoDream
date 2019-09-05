<?php
namespace Module\Game\TCG\Service\Admin;

use Module\ModuleController;

class Controller extends ModuleController {

    public $layout = '/Admin/layouts/main';

    protected function rules() {
        return [
            '*' => 'administrator'
        ];
    }
}