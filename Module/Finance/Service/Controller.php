<?php
namespace Module\Finance\Service;

use Module\ModuleController;

class Controller extends ModuleController {

    public $layout = 'main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function redirectWithMessage($url, $message, $time = 4, $status = 404) {
        return $this->show('@root/Admin/prompt', compact('url', 'message', 'time'));
    }

}