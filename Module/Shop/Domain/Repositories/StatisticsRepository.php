<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\OrderModel;

final class StatisticsRepository {

    public static function subtotal(): array {

        return [];
    }

    public static function userCount(int $user): array {
        return [
            [
                'name' => '订单数量',
                'count' => OrderModel::where('user_id', $user)->count(),
                'unit' => '个',
            ],
        ];
    }
}