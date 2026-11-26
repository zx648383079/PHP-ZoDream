<?php
declare(strict_types=1);
namespace Module\Disk\Service;

use Module\ModuleController;
use Zodream\Disk\File;

abstract class Controller extends ModuleController {

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