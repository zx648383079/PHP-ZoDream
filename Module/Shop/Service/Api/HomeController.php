<?php
namespace Module\Shop\Service\Api;

class HomeController extends Controller {

    public function indexAction() {
        return $this->jsonSuccess('api version v1');
    }
}