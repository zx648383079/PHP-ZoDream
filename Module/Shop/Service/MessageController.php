<?php
declare(strict_types=1);
namespace Module\Shop\Service;

class MessageController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {

        return $this->sendWithShare()->show();
    }
}