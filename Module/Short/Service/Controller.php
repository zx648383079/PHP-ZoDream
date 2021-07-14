<?php
namespace Module\Short\Service;

use Module\ModuleController;
use Zodream\Disk\File;

class Controller extends ModuleController {

    public File|string $layout = 'main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }

}