<?php
namespace Module\Shop\Service\Admin\Activity;

use Module\Shop\Service\Admin\Controller;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }

}