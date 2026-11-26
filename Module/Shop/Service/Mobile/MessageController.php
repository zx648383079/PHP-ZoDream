<?php
declare(strict_types=1);
namespace Module\Shop\Service\Mobile;


class MessageController extends Controller {

    public function indexAction() {
        $model_list = [1];
        return $this->show(compact('model_list'));
    }

    public function detailAction(int $id) {
        return $this->show();
    }
}