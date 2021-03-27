<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\Exam\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use AdminRole;

}