<?php
declare(strict_types=1);
namespace Module\MessageService\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\MessageService\Service\Api\Controller as RestController;

abstract class Controller extends RestController {

    use AdminRole;
}