<?php
declare(strict_types=1);
namespace Module\Book\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Book\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

}