<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        return compact();
    }
}