<?php
namespace Module\Game\Superstar\Service;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}