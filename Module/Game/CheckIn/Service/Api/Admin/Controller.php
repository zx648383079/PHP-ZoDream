<?php
declare(strict_types=1);
namespace Module\Game\CheckIn\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Game\CheckIn\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

}