<?php
declare(strict_types=1);
namespace Module\Counter\Service\Api;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

}