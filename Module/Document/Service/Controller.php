<?php
namespace Module\Document\Service;

use Module\ModuleController;
use Zodream\Disk\File;

class Controller extends ModuleController {

    public File|string $layout = 'main';

    public function redirectWithMessage($url, $message, $time = 4, $status = 404) {
        return $this->show('@root/Home/404', compact('url', 'message', 'time'));
    }

}