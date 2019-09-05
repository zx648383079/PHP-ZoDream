<?php
namespace Module\Game\TCG\Service\Admin;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}