<?php
declare(strict_types=1);
namespace Module\AdSense\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController as BaseController;

class Controller extends BaseController {

    use AdminRole;
}