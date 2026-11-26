<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Admin;

class HomeController extends Controller {

    public function indexAction() {
        return $this->show();
    }
}