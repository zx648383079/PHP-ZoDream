<?php
namespace Module\SMS\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\SMS\Service\Api\Controller as RestController;

class Controller extends RestController {

    use AdminRole;
}