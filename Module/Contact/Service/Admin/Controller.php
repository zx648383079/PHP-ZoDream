<?php
namespace Module\Contact\Service\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController;

class Controller extends ModuleController {

    use AdminRole;

    public $layout = '/Admin/layouts/main';

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }
}