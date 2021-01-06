<?php
declare(strict_types=1);
namespace Module\Forum\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Forum\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

}