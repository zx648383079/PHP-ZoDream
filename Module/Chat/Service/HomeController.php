<?php
namespace Module\Chat\Service;


use Module\ModuleController;

class HomeController extends ModuleController {

    public $layout = 'main';

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->show();
    }

}