<?php
namespace Module\Shop\Service\Admin;

use Module\Shop\Domain\Model\OrderModel;

class OrderController extends Controller {

    public function indexAction() {
        $model_list = OrderModel::with('user')->page();
        return $this->show(compact('model_list'));
    }
}