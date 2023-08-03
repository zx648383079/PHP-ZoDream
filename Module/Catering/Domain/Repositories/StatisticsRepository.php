<?php
declare(strict_types=1);
namespace Module\Catering\Domain\Repositories;


use Module\Catering\Domain\Models\StoreModel;

final class StatisticsRepository {

    public static function subtotal() {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $store_count = StoreModel::query()->count();
        $store_today = $store_count > 0 ? StoreModel::where('created_at', '>=', $todayStart)->count() : 0;

        return compact('store_count', 'store_today');
    }
}