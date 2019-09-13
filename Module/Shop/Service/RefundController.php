<?php
namespace Module\Shop\Service;


class RefundController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->sendWithShare()->show();
    }

    public function priceProtectAction() {
        return $this->sendWithShare()->show();
    }
}