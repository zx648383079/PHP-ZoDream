<?php
namespace Module\Contact\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Contact\Service\Api\Controller as RestController;

class Controller extends RestController {

    use AdminRole;
}