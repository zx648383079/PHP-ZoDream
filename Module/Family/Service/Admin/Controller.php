<?php
namespace Module\Family\Service\Admin;

use Module\ModuleController;


class Controller extends ModuleController {

    public $layout = '/Admin/layouts/main';

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    protected function getUrl($path, $args = []) {
        return url('./@admin/'.$path, $args);
    }

   public function redirectWithMessage($url, $message, $time = 4, $status = 404) {
       return $this->show('@root/Admin/prompt', compact('url', 'message', 'time'));
   }

    protected function processCustomRule($role) {
        if (auth()->user()->hasRole($role)) {
            return true;
        }
        return $this->redirectWithMessage('/',
            __('Not Role！')
            , 4,403);
    }
}