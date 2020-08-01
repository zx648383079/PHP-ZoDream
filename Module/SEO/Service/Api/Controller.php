<?php
namespace Module\SEO\Service\Api;

use Module\Auth\Domain\Concerns\AdminRole;
use Zodream\Route\Controller\RestController;

class Controller extends RestController {

    use AdminRole;
}