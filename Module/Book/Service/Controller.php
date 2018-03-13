<?php
namespace Module\Book\Service;

use Module\Book\Domain\Model\BookCategoryModel;
use Module\ModuleController;

class Controller extends ModuleController {

    public function prepare() {
        $cat_list = BookCategoryModel::all();
        $site_name = 'ZoDream 读书';
        $hot_keywords = [];
        $this->send(compact('cat_list', 'site_name', 'hot_keywords'));
    }
}