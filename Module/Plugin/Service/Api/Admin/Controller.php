<?php
declare(strict_types=1);
namespace Module\Plugin\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\OpenPlatform\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {
    use AdminRole;
}