<?php
namespace Module\Shop\Service\Admin;

use Module\ModuleController;
use Zodream\Service\Routing\Url;

class Controller extends ModuleController {

    protected function getUrl($path, $args = []) {
        return (string)Url::to('./admin/'.$path, $args);
    }
}