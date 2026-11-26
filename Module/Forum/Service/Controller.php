<?php
declare(strict_types=1);
namespace Module\Forum\Service;

use Module\ModuleController;
use Zodream\Disk\File;


abstract class Controller extends ModuleController {

    public function findLayoutFile(): File|string {
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }
}