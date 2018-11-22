<?php
namespace Module\Shop\Service\Mobile;

use Module\ModuleController;
use Module\Shop\Domain\Model\ArticleModel;
use Module\Shop\Domain\Model\CategoryModel;


class Controller extends ModuleController {

    public $layout = '/Mobile/layouts/main';

    public function prepare() {

    }

    protected function getUrl($path, $args = []) {
        return url('./mobile/'.$path, $args);
    }


    public function redirectWithAuth() {
        return $this->redirect('./mobile/member/login', ['redirect_uri' => url()->full()]);
    }
}