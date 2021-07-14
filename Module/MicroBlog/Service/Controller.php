<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service;

use Module\ModuleController;
use Zodream\Disk\File;

abstract class Controller extends ModuleController {


    public function findLayoutFile(): File|string {
        if ($this->httpContext('action') !== 'index') {
            return '';
        }
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }

    public function redirectWithAuth() {
        return $this->redirect([config('auth.home'), 'redirect_uri' => url('./')]);
    }
}