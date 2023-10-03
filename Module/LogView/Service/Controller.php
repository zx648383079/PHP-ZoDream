<?php
declare(strict_types=1);
namespace Module\LogView\Service;

use Module\ModuleController;
use Zodream\Disk\File;

class Controller extends ModuleController {

    protected File|string $layout = 'main';
}