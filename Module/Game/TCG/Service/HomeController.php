<?php
namespace Module\Game\TCG\Service;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}