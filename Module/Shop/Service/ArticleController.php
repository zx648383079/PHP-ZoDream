<?php
namespace Module\Shop\Service;

class ArticleController extends Controller {

    public function indexAction($id = null) {
        if ($id > 0) {
            return $this->detailAction(intval($id));
        }
        return $this->show();
    }

    public function detailAction($id) {

        return $this->show();
    }

    public function categoryAction($id) {
        return $this->show();
    }
}