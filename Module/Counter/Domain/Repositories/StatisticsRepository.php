<?php
declare(strict_types=1);
namespace Module\Counter\Domain\Repositories;


class StatisticsRepository {

    public static function subtotal() {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));

        return compact('');
    }

    public static function today() {
        $time = strtotime(date('Y-m-d 00:00:00'));
        $today = StateRepository::statisticsByTime($time, $time + 86400);
        $yesterday = StateRepository::statisticsByTime($time - 86400, $time);
        $start = strtotime(date('Y-m-d H:00:00'));
        $yesterdayHour = StateRepository::statisticsByTime(
            $start, $start + 3600
        );
        $scale = (time() - $time) / 86400;
        $expectToday = [];
        foreach ($today as $k => $val) {
            $expectToday[$k] = intval($val / $scale);
        }
        return compact('today', 'yesterday', 'expectToday', 'yesterdayHour');
    }
}