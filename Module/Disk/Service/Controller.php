<?php
namespace Module\Disk\Service;

use Module\ModuleController;
use Zodream\Disk\File;

class Controller extends ModuleController {

    protected File|string $layout = 'main';

    public function rules() {
        return [
          '*' => '@'
        ];
    }

    public static function noFound() {
        throw new \ErrorException('404');
    }
}