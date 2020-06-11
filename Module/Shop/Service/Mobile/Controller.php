<?php
namespace Module\Shop\Service\Mobile;

use Module\ModuleController;
use Module\Shop\Domain\Models\ArticleModel;
use Module\Shop\Domain\Models\CategoryModel;


class Controller extends ModuleController {

    public $layout = '/Mobile/layouts/main';

    protected function getUrl($path, $args = []) {
        return url('./mobile/'.$path, $args);
    }

    protected function runActionMethod($action, $vars = array()) {
        return $this->redirect('/');
    }


    public function redirectWithAuth() {
        return $this->redirect(['./mobile/member/login', 'redirect_uri' => url()->full()]);
    }
}