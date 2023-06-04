<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Service\Api;

use Module\ModuleController;
abstract class Controller extends ModuleController {

    public function rules() {
        return [
            '*' => '@'
        ];
    }
}