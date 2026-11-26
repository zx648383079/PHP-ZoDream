<?php
declare(strict_types=1);
namespace Module\Game\TCG\Service\Admin;

use Module\ModuleController;
use Zodream\Disk\File;

abstract class Controller extends ModuleController {

    protected File|string $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => 'administrator'
        ];
    }
}