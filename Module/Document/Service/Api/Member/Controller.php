<?php
declare(strict_types=1);
namespace Module\Document\Service\Api\Member;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\Document\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {
    use CheckRole;

    public function rules() {
        return [
            '*' => '@'
        ];
    }
}