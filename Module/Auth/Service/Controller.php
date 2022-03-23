<?php
namespace Module\Auth\Service;

use Module\ModuleController;

abstract class Controller extends ModuleController {

    public function redirectWithMessage(mixed $url, string $message, int $time = 4, int $status = 404) {
        return $this->show('@root/Home/404', compact('url', 'message', 'time'));
    }
}