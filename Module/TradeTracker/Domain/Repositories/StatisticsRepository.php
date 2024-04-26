<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Repositories;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $log_count = 0;
        return compact('log_count');
    }
}