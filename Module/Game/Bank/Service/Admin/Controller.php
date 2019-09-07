<?php
namespace Module\Game\Bank\Service\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController;

class Controller extends ModuleController {

    use AdminRole;

    public $layout = '/Admin/layouts/main';


}