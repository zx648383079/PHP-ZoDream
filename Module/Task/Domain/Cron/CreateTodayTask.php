<?php
declare(strict_types=1);
namespace Module\Task\Domain\Cron;


use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskPlanModel;

class CreateTodayTask {

    public function __construct() {
        $this->create();
    }

    public function create() {
        // 去重排序，重复按最大次数取
        $items = [];
        foreach ([
                     $this->getByDate(),
                     $this->getByWeek(),
                     $this->getByMonth()
                 ] as $data) {
            foreach ($data as $item) {
                $key = sprintf('%d-%d', $item['task_id'], $item['user_id']);
                if (!isset($items[$key]) || $items[$key]['amount'] < $item['amount']) {
                    $items[$key] = $item;
                }
            }
        }
        $items = array_values($items);
        usort($items, function ($a, $b) {
           return $a['priority'] < $b['priority'] ? -1 : 1;
        });
        $now = time();
        $day = date('Y-m-d');
        TaskDayModel::query()->insert(array_map(function ($item) use ($now, $day) {
            return [
                'user_id' => $item['user_id'],
                'task_id' => $item['task_id'],
                'today' => $day,
                'amount' => $item['amount'],
                'status' => TaskDayModel::STATUS_NONE,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $items));
    }

    protected function getByDate(): array {
        $startAt = strtotime(date('Y-m-d 00:00:00'));
        $endAt = $startAt + 86400;
        return TaskPlanModel::where('plan_type', 0)
            ->where('plan_time', '>=', $startAt)
            ->where('plan_time', '<', $endAt)->get();
    }

    protected function getByWeek(): array {
        $day = date('w');
        return TaskPlanModel::where('plan_type', 1)
            ->where('plan_time', $day < 1 ? 7 : $day)->get();
    }

    protected function getByMonth(): array {
        $day = date('d');
        return TaskPlanModel::where('plan_type', 2)
            ->where('plan_time', $day)->get();
    }
}