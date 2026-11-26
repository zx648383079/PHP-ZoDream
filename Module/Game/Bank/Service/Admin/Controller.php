<?php
declare(strict_types=1);
namespace Module\Game\Bank\Service\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController;
use Zodream\Disk\File;

abstract class Controller extends ModuleController {

    use AdminRole;

    protected File|string $layout = '/Admin/layouts/main';


}