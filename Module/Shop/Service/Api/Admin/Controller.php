<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\ModuleController as RestController;

class Controller extends RestController {

    use CheckRole;

    public function rules() {
        return [
            '*' => 'shop_admin'
        ];
    }
}