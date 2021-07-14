<?php
namespace Module\Game\TCG\Service\Admin;

use Module\ModuleController;
use Zodream\Disk\File;

class Controller extends ModuleController {

    public File|string $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => 'administrator'
        ];
    }
}