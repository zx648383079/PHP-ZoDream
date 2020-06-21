<?php
namespace Module\Exam\Service\Admin;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\ModuleController;

class Controller extends ModuleController {

    use CheckRole;

    public $layout = '/Admin/layouts/main';

    protected function rules() {
        return [
            '*' => 'exam_admin'
        ];
    }

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }

}