<?php
namespace Module\Contact\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Zodream\Route\Controller\RestController;

class Controller extends RestController {

    use AdminRole;
}