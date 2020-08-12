<?php
namespace Module\Note\Service\Admin;

use Module\Auth\Domain\Concerns\AdminRole;
use Module\ModuleController;


class Controller extends ModuleController {

    use AdminRole;

    public $layout = '/Admin/layouts/main';

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }

   public function redirectWithMessage($url, $message, $time = 4, $status = 404) {
       return $this->show('@root/Admin/prompt', compact('url', 'message', 'time'));
   }
}