<?php
namespace Module\Counter\Service;

use Module\Counter\Domain\Events\Visit;
use Module\ModuleController as Controller;

class HomeController extends Controller {

    public function indexAction() {
        event(Visit::createCurrent());
        return $this->show();
    }
}