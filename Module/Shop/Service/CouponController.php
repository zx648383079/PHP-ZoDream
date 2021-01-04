<?php
namespace Module\Shop\Service;


class CouponController extends Controller {

    public function rules() {
        return [
            'index' => '*',
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->sendWithShare()->show();
    }

    public function myAction() {
        return $this->sendWithShare()->show();
    }
}