<?php
namespace Module\CMS\Service;

class HomeController extends Controller {

    public function indexAction() {
        $title = '首页';
        return $this->show(compact('title'));
    }
}