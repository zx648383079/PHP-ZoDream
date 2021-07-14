<?php
namespace Module\Career\Service;

use Module\ModuleController;
use Zodream\Disk\File;


abstract class Controller extends ModuleController {
    public File|string $layout = 'main';


    public function findLayoutFile(): File|string {
        if ($this->layout === '') {
            return '';
        }
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }
}