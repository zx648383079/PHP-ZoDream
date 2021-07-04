<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;


use Module\Shop\Domain\Models\WarehouseGoodsModel;
use Module\Shop\Domain\Models\WarehouseModel;
use Module\Shop\Domain\Models\WarehouseRegionModel;

class WarehouseRepository {

    public static function getByRegion(int $regionId, int $goodsId, int $productId = 0): ?array {
        if ($regionId < 1) {
            return null;
        }
        $idItems = WarehouseRegionModel::whereIn('region_id', RegionRepository::getPathId($regionId))
            ->pluck('warehouse_id');
        if (empty($idItems)) {
            return null;
        }
        $goods = WarehouseGoodsModel::whereIn('warehouse_id', $idItems)
            ->where('goods_id', $goodsId)
            ->where('product_id', $productId)
            ->orderBy('amount', 'desc')
            ->first();
        if (empty($goods)) {
            return null;
        }
        $model = WarehouseModel::find($goods->warehouse_id);
        if (empty($model)) {
            return null;
        }
        $data = $model->toArray();
        $data['stock'] = $goods->amount;
        return $data;
    }

    public static function getStock(int $regionId, int $goodsId, int $productId = 0): int {
        if ($regionId < 1) {
            return 0;
        }
        $idItems = WarehouseRegionModel::whereIn('region_id', RegionRepository::getPathId($regionId))
            ->pluck('warehouse_id');
        if (empty($idItems)) {
            return 0;
        }
        $amount = WarehouseGoodsModel::whereIn('warehouse_id', $idItems)
            ->where('goods_id', $goodsId)
            ->where('product_id', $productId)
            ->orderBy('amount', 'desc')
            ->value('amount');
        return intval($amount);
    }
}