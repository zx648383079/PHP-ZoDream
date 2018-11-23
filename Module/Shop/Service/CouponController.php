<?php
namespace Module\Shop\Service;


class CouponController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->sendWithShare()->show();
    }
}