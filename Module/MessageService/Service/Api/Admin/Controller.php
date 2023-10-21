<?php
namespace Module\MessageService\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\MessageService\Service\Api\Controller as RestController;

class Controller extends RestController {

    use AdminRole;
}