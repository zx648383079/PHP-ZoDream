<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api\Merchant;

use Module\Catering\Service\Api\Controller as BaseController;

abstract class Controller extends BaseController {
    public function rules() {
        return [
            '*' => '@'
        ];
    }
}