<?php
namespace Module\Shop\Service\Admin;

use Module\ModuleController;
use Zodream\Service\Routing\Url;

class Controller extends ModuleController {

    public $layout = '/Admin/layouts/main';

    protected function getUrl($path, $args = []) {
        return (string)Url::to('./admin/'.$path, $args);
    }

    public function redirectWithMessage($url, $message, $time = 4, $status = 404) {
        return $this->show('/admin/prompt', compact('url', 'message', 'time'));
    }
}