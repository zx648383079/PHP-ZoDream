<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Repositories;

use Module\Finance\Domain\Entities\AccountEntity;
use Module\Finance\Domain\Entities\LogEntity;
use Module\Finance\Domain\Model\LogModel;
use Module\Finance\Domain\Model\BudgetModel;
use Zodream\Helpers\Time;

final class StatisticsRepository {

    public static function subtotal(string $startAt = '', string $endAt = '', int $type = 0): array {
        $startTime = empty($startAt) ? 0 : strtotime($startAt);
        $endTime = empty($endAt) ? 0 : strtotime($endAt);
        $typeStart = $startTime < 1 ? time() : $startTime;
        list($currentStart, $currentEnd, $lastStart, $lastEnd) = self::getRange($typeStart, $type);
        $startAt = $startTime <= 0 ? '' : date('Y-m-d H:i:s', $startTime);
        $endAt = $endTime <= 0 ? '' : date('Y-m-d H:i:s', $endTime);
        $money_total = AccountEntity::auth()->sum('money + frozen_money');
        $expenditure_total = LogEntity::auth()->where('type', LogEntity::TYPE_EXPENDITURE)
            ->when(!empty($startAt), function ($query) use ($startAt) {
                $query->where('happened_at', '>=', $startAt);
            })
            ->when(!empty($endAt), function ($query) use ($endAt) {
                $query->where('happened_at', '<=', $endAt);
            })
            ->sum('money');
        $expenditure_count = LogEntity::auth()->where('type', LogEntity::TYPE_EXPENDITURE)
            ->where('happened_at', '>=', $currentStart)->where('happened_at', '<', $currentEnd)
            ->count();
        $expenditure_current = LogEntity::auth()->where('type', LogEntity::TYPE_EXPENDITURE)
            ->where('happened_at', '>=', $currentStart)->where('happened_at', '<', $currentEnd)
            ->sum('money');
        $expenditure_last = LogEntity::auth()->where('type', LogEntity::TYPE_EXPENDITURE)
            ->where('happened_at', '>=', $lastStart)->where('happened_at', '<', $lastEnd)
            ->sum('money');
        $income_total = LogEntity::auth()->where('type', LogEntity::TYPE_INCOME)
            ->when(!empty($startAt), function ($query) use ($startAt) {
                $query->where('happened_at', '>=', $startAt);
            })
            ->when(!empty($endAt), function ($query) use ($endAt) {
                $query->where('happened_at', '<=', $endAt);
            })
            ->sum('money');
        $income_current = LogEntity::auth()->where('type', LogEntity::TYPE_INCOME)
            ->where('happened_at', '>=', $currentStart)->where('happened_at', '<', $currentEnd)
            ->count();
        $income_current = LogEntity::auth()->where('type', LogEntity::TYPE_INCOME)
            ->where('happened_at', '>=', $currentStart)->where('happened_at', '<', $currentEnd)
            ->sum('money');
        $income_last = LogEntity::auth()->where('type', LogEntity::TYPE_INCOME)
            ->where('happened_at', '>=', $lastStart)->where('happened_at', '<', $lastEnd)
            ->sum('money');
        $lend_total = LogEntity::auth()->where('type', LogEntity::TYPE_LEND)
            ->sum('money');
        $borrow_total = LogEntity::auth()->where('type', LogEntity::TYPE_BORROW)
            ->sum('money');
        $stage_items = static::getStage($startAt, $endAt, $type);
        return compact('money_total',
            'expenditure_total', 'expenditure_current', 'expenditure_last', 'expenditure_count',
            'income_total', 'income_current', 'income_last', 'income_count', 
            'stage_items', 'lend_total', 'borrow_total'
        );
    }

    public static function bugetWithMonth(string $startAt, string $endAt): array {
        if (empty($startAt) && empty($endAt)) {
            $startAt = date('Y-01-01');
        }
        $data = BudgetModel::auth()->where('deleted_at', 0)
            ->orderBy('id', 'asc')->asArray()->get('id', 'name');
        $res = [];
        foreach($data as $item) {
            $id = intval($item['id']);
            $res[$id] = [
                'id' => $id,
                'name' => $item['name'],
                'total' => 0,
                'items' => []
            ];
        }
        LogModel::auth()->sumByDate('%Y%m')
            ->where('type', LogEntity::TYPE_EXPENDITURE)
            ->where('budget_id', '>', 0)
            ->when(!empty($startAt), function ($query) use ($startAt) {
                $query->where('happened_at', '>=', $startAt);
            })
            ->when(!empty($endAt), function ($query) use ($endAt) {
                $query->where('happened_at', '<=', $endAt);
            })->orderBy('day', 'asc')
            ->groupBy('day', 'budget_id')
            ->asArray()->each(function($item) use(&$res) {
                $id = intval($item['budget_id']);
                $money = floatval($item['money']);
                $res[$id]['total'] += $money;
                $res[$id]['items'][] = [
                    'date' => $item['day'],
                    'money' => $money
                ];
            }, 'budget_id');
        return array_values($res);
    }

    public static function getStage(string $startAt, string $endAt, int $type) {
        if (empty($startAt) && empty($endAt)) {
            if ($type < 1) {
                list($startAt, $endAt) = Time::month(time());
            } elseif ($type === 1) {
                $startAt = date('Y-01-01');
            }
        }
        $format = '%Y';
        if ($type < 1) {
            $format = '%Y%m%d';
        } elseif ($type < 3) {
            $format = '%Y%m';
        }
        $incomeItems = LogModel::auth()->sumByDate($format)->where('type', LogEntity::TYPE_INCOME)
            ->when(!empty($startAt), function ($query) use ($startAt) {
                $query->where('happened_at', '>=', $startAt);
            })
            ->when(!empty($endAt), function ($query) use ($endAt) {
                $query->where('happened_at', '<=', $endAt);
            })->orderBy('day', 'asc')->asArray()->get();
        $expenditureItems = LogModel::auth()->sumByDate($format)->where('type', LogEntity::TYPE_EXPENDITURE)
            ->when(!empty($startAt), function ($query) use ($startAt) {
                $query->where('happened_at', '>=', $startAt);
            })
            ->when(!empty($endAt), function ($query) use ($endAt) {
                $query->where('happened_at', '<=', $endAt);
            })->orderBy('day', 'asc')->asArray()->get();
        $data = [];
        foreach ($incomeItems as $item) {
            $data[$item['day']] = [
                'date' => $item['day'],
                'income' => floatval($item['money']),
                'expenditure' => 0,
            ];
        }
        foreach ($expenditureItems as $item) {
            if (isset($data[$item['day']])) {
                $data[$item['day']]['expenditure'] = floatval($item['money']);
                continue;
            }
            $data[$item['day']] = [
                'date' => $item['day'],
                'income' => 0,
                'expenditure' => floatval($item['money']),
            ];
        }
        ksort($data);
        return array_values($data);
    }

    /**
     * 获取当月，上月
     * @param int $time
     * @param int $type
     * @return string[]
     */
    private static function getRange(int $time, int $type = 0) : array {
        if ($type < 1) {
            $start = strtotime(date('Y-m-d 00:00:00', $time));
            $current = date('Y-m-d', $start);
            return [$current, date('Y-m-d', $start + 86400), date('Y-m-d', $start - 86400), $current];
        }
        $year = intval(date('Y', $time));
        if ($type === 1) {
            $month = intval(date('m'));
            $data = [date(sprintf('%d-%d-01', $year, $month)), '', '', ''];
            list($year, $month) = static::formatYearMonth($year, $month + 1);
            $data[1] = date(sprintf('%d-%d-01', $year, $month));
            list($year, $month) = static::formatYearMonth($year, $month - 2);
            $data[2] = date(sprintf('%d-%d-01', $year, $month));
            $data[3] = $data[0];
            return $data;
        }
        if ($type === 2) {
            $month = intval(date('m'));
            $month = intval(floor(($month - 1) / 3) * 3 + 1);
            $data = [date(sprintf('%d-%d-01', $year, $month)), '', '', ''];
            list($year, $month) = static::formatYearMonth($year, $month + 3);
            $data[1] = date(sprintf('%d-%d-01', $year, $month));
            list($year, $month) = static::formatYearMonth($year, $month - 6);
            $data[2] = date(sprintf('%d-%d-01', $year, $month));
            $data[3] = $data[0];
            return $data;
        }
        $data = [date(sprintf('%d-01-01', $year)), '', '', ''];
        $data[1] = date(sprintf('%d-01-01', $year + 1));
        $data[2] = date(sprintf('%d-01-01', $year - 1));
        $data[3] = $data[0];
        return $data;
    }

    /**
     * 正确转化年月
     * @param int $year
     * @param int $month
     * @return array{int, int}
     */
    private static function formatYearMonth(int $year, int $month): array {
        if ($month >= 1 && $month <= 12) {
            return [$year, $month];
        }
        $month --;
        $yearAdd = floor($month / 12);
        $month = $month % 12;
        if ($yearAdd < 0) {
            $month = 12 + $month;
        }
        return [intval($year + $yearAdd), $month + 1];
    }
}