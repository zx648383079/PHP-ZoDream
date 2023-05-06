<?php
declare(strict_types=1);
namespace Module\Counter\Domain\Repositories;

use Module\Counter\Domain\Model\LogModel;

class StatisticsRepository {

    public static function subtotal(string $type) {
        list($startTime, $endTime) = StateRepository::getTimeRange($type);
        $stage_items = static::getStage($startTime, $endTime, $type);
        return compact('stage_items');
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


    public static function getStage(int $startAt, int $endAt, string $type) {
        $format = 'H';
        switch ($type) {
            case 'week':
                $format = 'w';
                break;
            case 'month':
                $format = 'd';
                break;
        }
        $items = [];
        LogModel::query()->where('created_at', '>=', $startAt)
            ->where('created_at', '<=', $endAt)
            ->each(function ($item) use (&$items, $format) {
                $date = date($format, intval($item['created_at']));
                if (!isset($items[$date])) {
                    $items[$date] = [
                        0,
                        []
                    ];
                }
                $items[$date][0] ++;
                if (!in_array($item['ip'], $items[$date][1])) {
                    $items[$date][1][] = $item['ip'];
                }
            }, 'ip', 'created_at');
        $start = 0;
        $end = 23;
        switch ($type) {
            case 'week':
                $end = 6;
                break;
            case 'month':
                $end = intval(date('t'));
                break;
        }
        $data = [];
        for (;$start <= $end; $start ++) {
            if (!isset($items[$start])) {
                $data[] = [
                  'date' => $start,
                  'pv' => 0,
                  'uv' => 0,
                ];
                continue;
            }
            $data[] = [
                'date' => $start,
                'pv' => $items[$start][0],
                'uv' => count($items[$start][1]),
            ];
        }
        return $data;
    }

    public static function trendAnalysis(string $type = 'today', int $compare = 0) {
        list($startTime, $endTime) = StateRepository::getTimeRange($type);
        $items = static::getStage($startTime, $endTime, $type);
        $compare_items = [];
        if ($type === 'week' || $type === 'month') {
            $compare = 0;
        }
        if ($compare === 1) {
            $compare_items = static::getStage($startTime - 86400, $endTime - 86400, $type);
        } elseif ($compare === 2) {
            $compare_items = static::getStage($startTime - 86400 * 7, $endTime - 86400 * 7, $type);
        }
        return compact('items', 'compare_items');
    }
}