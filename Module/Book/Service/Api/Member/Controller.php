<?php
declare(strict_types=1);
namespace Module\Book\Service\Api\Member;

use Module\Legwork\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

}