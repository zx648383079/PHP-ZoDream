<?php
namespace Module\Shop\Service\Admin;

use Module\ModuleController;

class Controller extends ModuleController {

    protected function getUrl($path, $args = []) {
        return (string)Url::to('./admin/'.$path, $args);
    }
}