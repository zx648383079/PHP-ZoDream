<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\CartModel;
use Module\Shop\Domain\Models\ShippingModel;
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

    /**
     * 计算配送费
     * @param ShippingModel $shipping
     * @param array $settings
     * @param CartModel[] $goods_list
     * @return float
     */
    public static function getFee(ShippingModel $shipping, array $settings, array $goods_list) {
        $amount = 0;
        $price = 0;
        $weight = 0;
        foreach ($goods_list as $item) {
            $amount += $item->amount;
            $price += $item->getTotalAttribute();
            $weight += $item->goods->weight * $item->amount;
        }
        $instance = Manager::shipping($shipping->code);
        return $instance->calculate($settings, $amount, $price, $weight);
    }
}