<?php
namespace Module\Shop\Service\Mobile;

class CategoryController extends Controller {

    public function indexAction($id) {
        return $this->show();
    }
}