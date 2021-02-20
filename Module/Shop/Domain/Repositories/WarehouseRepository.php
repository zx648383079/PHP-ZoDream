<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\RegionModel;
use Module\Shop\Domain\Models\WarehouseGoodsModel;
use Module\Shop\Domain\Models\WarehouseLogModel;
use Module\Shop\Domain\Models\WarehouseModel;
use Module\Shop\Domain\Models\WarehouseRegionModel;

class WarehouseRepository {
    public static function getList(string $keywords = '') {
        return WarehouseModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'link_user', 'tel', 'address']);
        })->page();
    }

    public static function get(int $id) {
        return WarehouseModel::findOrThrow($id, '数据有误');
    }

    public static function getWithRegion(int $id) {
        $model = static::get($id);
        $model->region = static::getRegion($id);
        return $model;
    }

    public static function getRegion(int $id) {
        $region = WarehouseRegionModel::where('warehouse_id', $id)->pluck('region_id');
        if (empty($region)) {
            return [];
        }
        return RegionModel::whereIn('id', $region)
            ->get(['id', 'name']);
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = WarehouseModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        if (!isset($data['region'])) {
            return $model;
        }
        list($add, $_, $del) = ModelHelper::splitId(array_map(function ($item) {
            return is_array($item) ? $item['id'] : (string)$item;
        }, $data['region']),
            WarehouseRegionModel::where('warehouse_id', $model->id)->pluck('region_id'));
        if (!empty($del)) {
            WarehouseRegionModel::where('warehouse_id', $model->id)->whereIn('region_id', $del)->delete();
        }
        if (!empty($add)) {
            WarehouseRegionModel::query()->insert(array_map(function ($region_id) use ($model) {
                return [
                    'region_id' => $region_id,
                    'warehouse_id' => $model->id
                ];
            }, $add));
        }
        return $model;
    }

    public static function remove(int $id) {
        WarehouseModel::where('id', $id)->delete();
        WarehouseGoodsModel::where('warehouse_id', $id)->delete();
        WarehouseRegionModel::where('warehouse_id', $id)->delete();
    }

    public static function goodsList(string $keywords = '', int $warehouse = 0, int $goods = 0, int $product = 0) {

        return WarehouseGoodsModel::query()->with('warehouse', 'goods', 'product')
            ->when(!empty($keywords), function ($query) {
                $goodsId = SearchModel::searchWhere(GoodsModel::query(), ['name'])
                    ->pluck('id');
                if (empty($goodsId)) {
                    return $query->isEmpty();
                }
                $query->whereIn('goods_id', $goodsId);
            })->when($warehouse > 0, function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse);
            })->when($goods > 0, function ($query) use ($goods) {
                $query->where('goods_id', $goods);
            })->when($product > 0, function ($query) use ($product) {
                $query->where('product_id', $product);
            })->page();
    }

    public static function logList(string $keywords = '', int $warehouse = 0, int $goods = 0, int $product = 0) {
        return WarehouseLogModel::query()->with('warehouse', 'goods', 'user')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['remark']);
            })->when($warehouse > 0, function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse);
            })->when($goods > 0, function ($query) use ($goods) {
                $query->where('goods_id', $goods);
            })->when($product > 0, function ($query) use ($product) {
                $query->where('product_id', $product);
            })->page();
    }
}