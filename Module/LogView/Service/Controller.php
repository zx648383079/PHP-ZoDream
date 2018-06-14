<?php
namespace Module\LogView\Service;

use Module\ModuleController;

class Controller extends ModuleController {

    public $layout = 'main';



    public function redirectWithMessage($url, $message, $time = 4, $status = 404) {
        return $this->show('/prompt', compact('url', 'message', 'time'));
    }
}