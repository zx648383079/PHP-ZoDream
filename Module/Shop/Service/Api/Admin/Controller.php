<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Auth\Domain\Concerns\CheckRole;
use Zodream\Route\Controller\RestController;

class Controller extends RestController {

    use CheckRole;

    protected function rules() {
        return [
            '*' => 'shop_admin'
        ];
    }
}