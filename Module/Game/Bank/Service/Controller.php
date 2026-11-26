<?php
declare(strict_types=1);
namespace Module\Game\Bank\Service;

use Module\ModuleController;
use Zodream\Disk\File;

abstract class Controller extends ModuleController {

    protected File|string $layout = 'main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }
}