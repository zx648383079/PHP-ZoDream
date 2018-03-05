<?php
namespace Module\Shop\Service\Mobile;

use Module\ModuleController;
use Module\Shop\Domain\Model\ArticleModel;
use Module\Shop\Domain\Model\CategoryModel;

class Controller extends ModuleController {

    public function prepare() {
        $helper_list = ArticleModel::getHelps();
        $site_name = 'zodream shop';
        $hot_searches = [];
        $categories = CategoryModel::where('parent_id', 0)->all();
        $this->send(compact('helper_list', 'site_name', 'categories', 'hot_searches'));
    }
}