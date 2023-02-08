<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Blog\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

}