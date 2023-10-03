<?php
namespace Module\Exam\Service;

use Module\ModuleController;
use Zodream\Disk\File;


class Controller extends ModuleController {
    protected File|string $layout = 'main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function findLayoutFile(): File|string {
        if ($this->layout === '') {
            return '';
        }
        return app_path()->file('UserInterface/Home/layouts/main.php');
    }
}