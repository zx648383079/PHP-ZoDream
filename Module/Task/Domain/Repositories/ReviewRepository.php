<?php
namespace Module\Task\Domain\Repositories;

use Module\Task\Domain\Model\LogPageModel;
use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Exception;
use Zodream\Database\Model\Query;
use Zodream\Helpers\Time;
use Zodream\Html\Page;

class ReviewRepository {

    /**
     * 统计
     * @param string $start_at
     * @param string $end_at
     * @param bool $ignoreEmpty
     * @return array
     * @throws Exception
     */
    public static function statistics(string $start_at, string $end_at, $ignoreEmpty = false) {
        $data = TaskDayModel::with('logs')->time($start_at, $end_at)
            ->where('user_id', auth()->id())
            ->orderBy('today', 'asc')->get();
        $days = Time::rangeDate($start_at, $end_at);
        return self::formatStatistics($days, $data, $ignoreEmpty);
    }

    public static function dayStatistics(string  $day) {
        $data = TaskDayModel::with('logs')
            ->where('today', $day)
            ->where('user_id', auth()->id())
            ->orderBy('today', 'asc')->get();
        $items = self::formatStatistics([$day], $data);
        return reset($items);
    }

    /**
     * @param $start_at
     * @param $end_at
     * @param bool $isAll
     * @return Page<LogPageModel>|LogPageModel[]
     * @throws Exception
     */
    public static function logList(int $start_at, int $end_at, $isAll = false) {
        /** @var Query $query */
        $query = LogPageModel::with('task')
            ->where('created_at', '>=', $start_at)
            ->where('created_at', '<=', $end_at)
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'asc');
        if ($isAll) {
            return $query->get();
        }
        return $query->page();
    }

    /**
     * @param $ignoreEmpty
     * @param array $days
     * @param $data
     * @return array
     */
    public static function formatStatistics(array $days, array $data, $ignoreEmpty = false) {
        $day_list = [];
        foreach ($days as $day) {
            $day_list[$day] = [
                'day' => $day,
                'week' => Time::weekFormat($day),
                'amount' => 0,
                'complete_amount' => 0,
                'success_amount' => 0,
                'pause_amount' => 0,
                'failure_amount' => 0,
                'total_time' => 0,
                'valid_time' => 0
            ];
        }
        foreach ($data as $item) {
            $day_list[$item->today]['amount'] += $item->amount + $item->success_amount;
            $day_list[$item->today]['success_amount'] += $item->success_amount;
            $day_list[$item->today]['pause_amount'] += $item->pause_amount;
            $day_list[$item->today]['failure_amount'] += $item->failure_amount;
            if ($item->amount < 1) {
                $day_list[$item->today]['complete_amount']++;
            }
            if (!$item->logs) {
                continue;
            }
            foreach ($item->logs as $log) {
                $day_list[$item->today]['total_time'] += $log->time;
                if ($log->status === TaskLogModel::STATUS_FINISH) {
                    $day_list[$item->today]['valid_time'] += $log->time;
                }
            }
        }
        if ($ignoreEmpty) {
            $day_list = array_filter($day_list, function ($item) {
                return $item['amount'] > 0;
            });
        }
        return array_values($day_list);
    }
}