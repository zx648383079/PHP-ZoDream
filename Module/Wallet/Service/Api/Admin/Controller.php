<?php
declare(strict_types=1);
namespace Module\Wallet\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Wallet\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;
}