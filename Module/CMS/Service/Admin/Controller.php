<?php
namespace Module\CMS\Service\Admin;

use Module\ModuleController;
use Zodream\Service\Routing\Url;

class Controller extends ModuleController {

    public $layout = '/Admin/layouts/main';

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function prepare() {
        $cat_list = [];
        $this->send(compact('cat_list'));
    }

    protected function getUrl($path, $args = []) {
        return (string)Url::to('./admin/'.$path, $args);
    }
}