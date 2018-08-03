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
}