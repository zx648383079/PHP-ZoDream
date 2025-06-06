<?php
declare(strict_types=1);
namespace Module\TradeTracker\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\TradeTracker\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

}