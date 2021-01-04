<?php
namespace Module\Auth\Service;

use Module\ModuleController;

abstract class Controller extends ModuleController {

    public function redirectWithMessage($url, $message, $time = 4, $status = 404) {
        return $this->show('@root/Home/404', compact('url', 'message', 'time'));
    }
}