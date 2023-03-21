<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Template\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

}