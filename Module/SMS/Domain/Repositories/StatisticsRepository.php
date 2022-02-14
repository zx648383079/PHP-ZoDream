<?php
declare(strict_types=1);
namespace Module\SMS\Domain\Repositories;

final class StatisticsRepository {

    public static function subtotal(): array {
        $today_count = 0;
        return compact('today_count');
    }
}