<?php
namespace Module\Game\Superstar\Service\Admin;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}