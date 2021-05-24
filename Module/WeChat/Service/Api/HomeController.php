<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api;

class HomeController extends Controller {

    public function indexAction() {
        return $this->renderData([]);
    }
}