<?php
declare(strict_types=1);
namespace Module\Legwork\Domain\Repositories;

use Module\Legwork\Domain\Model\OrderModel;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $order_count = OrderModel::query()->count();
        $order_today = OrderModel::where('created_at', '>=', $todayStart)->count();
        return compact('order_count', 'order_today');
    }
}