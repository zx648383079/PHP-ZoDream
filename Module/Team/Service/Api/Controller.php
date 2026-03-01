<?php
declare(strict_types=1);
namespace Module\Team\Service\Api;

use Module\ModuleController as BaseController;

abstract class Controller extends BaseController {
    public function rules() {
        return ['*' => '@'];
    }
}