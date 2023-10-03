<?php
namespace Module\Auth\Service;

use Module\ModuleController;
use Zodream\Disk\File;

abstract class Controller extends ModuleController {
    protected File|string $layout = 'main';
}