<?php
namespace Module\Shop\Service\Api\V1;

class HomeController extends Controller {

    public function indexAction() {
        return $this->jsonSuccess('api version v1');
    }
}