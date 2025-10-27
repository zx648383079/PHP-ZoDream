<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api\Member;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\ResourceStore\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    use CheckRole;

    public function rules() {
        return [
            '*' => '@'
        ];
    }
}