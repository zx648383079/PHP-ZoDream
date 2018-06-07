<?php
namespace Module\Shop\Service\Mobile;

use Module\ModuleController;
use Module\Shop\Domain\Model\ArticleModel;
use Module\Shop\Domain\Model\CategoryModel;
use Zodream\Service\Routing\Url;

class Controller extends ModuleController {

    public $layout = '/Mobile/layouts/main';

    public function prepare() {

    }

    protected function getUrl($path, $args = []) {
        return (string)Url::to('./mobile/'.$path, $args);
    }
}