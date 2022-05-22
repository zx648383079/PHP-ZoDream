<?php
declare(strict_types=1);
namespace Module\AppStore\Domain\Repositories;


use Module\AppStore\Domain\Models\AppModel;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $app_count = AppModel::query()->count();
        $app_today = $app_count < 1 ? 0 : AppModel::where('created_at', '>=', $todayStart)->count();
        return compact('app_count', 'app_today');
    }
}