<?php
declare(strict_types=1);
namespace Module\Catering\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Catering\Domain\Entities\PurchaseOrderEntity;
use Module\Catering\Domain\Entities\StoreStockEntity;

final class StockRepository {

    public static function merchantList(string $keywords = '') {
        return StoreStockEntity::query()
            ->where('store_id', StoreRepository::own())
            ->page();
    }

    public static function merchantOrderList(string $keywords) {
        return PurchaseOrderEntity::query()
            ->where('store_id', StoreRepository::own())
            ->page();
    }
}