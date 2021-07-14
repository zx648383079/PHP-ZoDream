<?php
namespace Module\Blog\Service;

use Module\ModuleController;
use Zodream\Disk\File;

abstract class Controller extends ModuleController {
    public File|string $layout = 'main';


    public function findLayoutFile(): File|string {
        if ($this->layout === '') {
            return '';
        }
        return app_path('UserInterface/Home/layouts/main.php');
    }
}