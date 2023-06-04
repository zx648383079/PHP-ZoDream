<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api\Maker;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Game\GameMaker\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

}