<?php
declare(strict_types=1);
namespace Module\Counter\Service;

use Module\ModuleController as Controller;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}