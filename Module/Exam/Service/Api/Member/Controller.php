<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api\Member;

use Module\Exam\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

}