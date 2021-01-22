<?php
declare(strict_types=1);
namespace Module\Video\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Video\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

}