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

    public function orderAction() {
        return $this->renderData([
            ['date' => '2020-06', 'count' => 6, 'total' => 666],
            ['date' => '2020-07', 'count' => 2, 'total' => 66],
            ['date' => '2020-08', 'count' => 0, 'total' => 6],
            ['date' => '2020-09', 'count' => 9, 'total' => 66],
            ['date' => '2020-10', 'count' => 11, 'total' => 1116],
        ]);
    }
}