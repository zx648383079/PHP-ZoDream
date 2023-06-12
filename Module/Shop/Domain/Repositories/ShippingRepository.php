<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Cart\ICartItem;
use Module\Shop\Domain\Entities\AddressEntity;
use Module\Shop\Domain\Models\ShippingGroupModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Domain\Models\ShippingRegionModel;
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

    public static function getByAddress(AddressEntity $address): array {
        return static::getByRegion($address->region_id);
    }

    public static function getByRegion(int $regionId): array {
        $groups = ShippingGroupModel::query()
            ->when($regionId > 0, function ($query) use ($regionId) {
                $itemId = ShippingRegionModel::query()
                    ->whereIn('region_id', RegionRepository::getPathId($regionId))
                    ->pluck('group_id');
                if (empty($itemId)) {
                    return;
                }
                $query->whereIn('id', $itemId);
            })
            ->orWhere('is_all', 1)
            ->pluck(null, 'shipping_id');
        if (empty($groups)) {
            return [];
        }
        $shipping_list = ShippingModel::query()->whereIn('id', array_keys($groups))
            ->get();
        if (empty($shipping_list)) {
            return [];
        }
        foreach ($shipping_list as $item) {
            if (!isset($groups[$item->id])) {
                unset($item);
                continue;
            }
            $item->settings = $groups[$item->id];
        }
        return $shipping_list;
    }

    /**
     * 根据配送地址获取配送设置
     * @param int $shipping
     * @param int $region
     * @return array|null
     */
    public static function getGroup(int $shipping, int $region): array|null {
        $groupId = ShippingRegionModel::where('shipping_id', $shipping)
            ->where('region_id', $region)->value('group_id');
        if ($groupId > 0) {
            return ShippingGroupModel::query()->where('id', $groupId)
                ->asArray()->first();
        }
        return ShippingGroupModel::query()->where('shipping_id', $shipping)
            ->where('is_all', 1)
            ->asArray()->first();
    }

    /**
     * 计算配送费
     * @param ShippingModel $shipping
     * @param array $settings
     * @param ICartItem[] $goods_list
     * @return float
     */
    public static function getFee(ShippingModel $shipping, array $settings, array $goods_list) {
        $amount = 0;
        $price = 0;
        $weight = 0;
        foreach ($goods_list as $item) {
            $amount += $item->amount;
            $price += $item->total();
            $weight += $item->goods->weight * $item->amount;
        }
        $instance = Manager::shipping($shipping->code);
        return $instance->calculate($settings, $amount, $price, $weight);
    }
}