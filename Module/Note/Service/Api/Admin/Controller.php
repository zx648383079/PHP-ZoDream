<?php
declare(strict_types=1);
namespace Module\Note\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\MicroBlog\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

}