<?php
declare(strict_types=1);
namespace Module\Catering\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Catering\Domain\Entities\GoodsEntity;

final class ProductRepository {

    public static function getList(int $store, string $keywords = '', int $category = 0) {
        return GoodsEntity::query()
            ->where('store_id', $store)
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, ['name'], true, '', $keywords);
            })->when($category > 0, function ($query) use ($category) {
               $query->where('cat_id', $category);
            })->page();
    }

    public static function get(int $store, int $id) {
        $model = GoodsEntity::query()
            ->where('store_id', $store)->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('product is error');
        }
        return $model;
    }

    public static function merchantList(string $keywords = '',
                                        int $category = 0) {
        return GoodsEntity::query()
            ->where('store_id', StoreRepository::own())
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, ['name'], true, '', $keywords);
            })->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })->page();
    }

    public static function merchantGet(int $id) {
        $model = GoodsEntity::query()
            ->where('store_id', StoreRepository::own())->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('product is error');
        }
        return $model;
    }
}