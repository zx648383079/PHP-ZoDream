<?php
namespace Module\Shop\Service;


class BonusController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->sendWithShare()->show();
    }
}