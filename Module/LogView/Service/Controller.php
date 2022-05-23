<?php
declare(strict_types=1);
namespace Module\LogView\Service;

use Module\ModuleController;
use Zodream\Disk\File;

class Controller extends ModuleController {

    public File|string $layout = 'main';



    public function redirectWithMessage($url, $message, $time = 4, $status = 404) {
        return $this->show('/prompt', compact('url', 'message', 'time'));
    }
}