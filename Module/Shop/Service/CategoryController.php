<?php
namespace Module\Shop\Service;

class CategoryController extends Controller {

    public function indexAction($id) {
        return $this->show();
    }
}