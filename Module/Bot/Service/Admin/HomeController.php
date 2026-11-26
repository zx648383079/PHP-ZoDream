<?php
declare(strict_types=1);
namespace Module\Bot\Service\Admin;

class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }
}