<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Repositories\Admin\OrderRepository;

class StatisticsController extends Controller {

    public function indexAction() {
        $subtotal = OrderRepository::getSubtotal();
        return $this->renderData($subtotal);
    }

    public function checkNewAction() {
        return $this->renderData(OrderRepository::checkNew());
    }
}