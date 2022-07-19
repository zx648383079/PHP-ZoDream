<?php
declare(strict_types=1);
namespace Module\Short\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Short\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

}