<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Plugin\Manager;

class PaymentRepository {

    public static function getPlugins() {
        $items = [];
        $data = Manager::all('payment');
        foreach ($data as $item) {
            $items[$item] = Manager::payment($item)->getName();
        }
        return $items;
    }
}