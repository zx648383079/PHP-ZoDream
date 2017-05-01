<?php
namespace Module\Blog\Service;

use Module\ModuleController;

class HomeController extends ModuleController {

    public function indexAction($keywords = null,
                                $category = null,
                                $sort = 'create_at',
                                $order = 'desc',
                                $page = 1,
                                $size = 20) {

        return $this->show();
    }

    public function detailAction($id) {
        return $this->show();
    }
}