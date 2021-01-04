<?php
namespace Module\Template\Service\Admin;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\ModuleController;

class Controller extends ModuleController {

    use CheckRole;

    public $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => 'template_admin'
        ];
    }

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }
}