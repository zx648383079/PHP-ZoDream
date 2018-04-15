<?php
namespace Module\Shop\Service\Api;

class GoodsController extends Controller {

    public function indexAction() {

        return $this->jsonSuccess('api version v1');
    }
}