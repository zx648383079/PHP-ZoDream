<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\ShippingGroupModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Domain\Models\ShippingRegionModel;
use Module\Shop\Domain\Plugin\Manager;

class ShippingRepository {

    public static function getPlugins(): array {
        $items = [];
        $data = Manager::all('shipping');
        foreach ($data as $item) {
            $items[$item] = Manager::shipping($item)->getName();
        }
        return $items;
    }

    public static function getList(string $keywords = '') {
        return ShippingModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
    }

    public static function get(int $id, bool $full = false) {
        $model = ShippingModel::findOrThrow($id);
        if (!$full) {
            return $model;
        }
        $data = $model->toArray();
        $data['groups'] = ShippingGroupModel::with('regions')
            ->where('shipping_id', $model->id)->get();
        return $data;
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = ShippingModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        if (!isset($data['groups'])) {
            return $model;
        }
        foreach ($data['groups'] as $item) {
            $item['shipping_id'] = $model->id;
            static::saveGroup($item);
        }
        return $model;
    }

    public static function saveGroup(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = ShippingGroupModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        static::saveRegion($model->is_all ? [] : array_column($data['regions'], 'id'), $model->id, $model->shipping_id);
    }

    public static function saveRegion(array $items, $group_id, $shipping_id) {
        $exist = ShippingRegionModel::where(compact('shipping_id', 'group_id'))
            ->pluck('region_id');
        $add = array_diff($items, $exist);
        $remove = array_diff($exist, $items);
        if (!empty($add)) {
            ShippingRegionModel::query()
                ->insert(array_map(function ($region_id) use ($group_id, $shipping_id) {
                return compact('region_id', 'group_id', 'shipping_id');
            }, $add));
        }
        if (!empty($remove)) {
            ShippingRegionModel::where(compact('shipping_id', 'group_id'))
                ->whereIn('region_id', $remove)->delete();
        }
    }

    public static function remove(int $id) {
        ShippingModel::where('id', $id)->delete();
        ShippingGroupModel::where('shipping_id', $id)->delete();
        ShippingRegionModel::where('shipping_id', $id)->delete();
    }

    public static function all() {
        return ShippingModel::query()->get('id', 'name');
    }

}