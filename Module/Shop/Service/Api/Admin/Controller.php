<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\Shop\Service\Api\Controller as RestController;

class Controller extends RestController {

    use CheckRole;

    public function rules() {
        return [
            '*' => 'shop_admin'
        ];
    }
}