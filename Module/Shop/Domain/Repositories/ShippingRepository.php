<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Plugin\Manager;

class ShippingRepository {

    public static function getPlugins() {
        $items = [];
        $data = Manager::all('shipping');
        foreach ($data as $item) {
            $items[$item] = Manager::shipping($item)->getName();
        }
        return $items;
    }
}