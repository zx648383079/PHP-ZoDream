<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Service;

use Module\ModuleController;
use Zodream\Disk\File;

class Controller extends ModuleController {

    protected File|string $layout = 'main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }

}