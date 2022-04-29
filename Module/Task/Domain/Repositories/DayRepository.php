<?php
declare(strict_types=1);
namespace Module\Task\Domain\Repositories;

use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskModel;

class DayRepository {

    public static function getList(string $day) {
        return TaskDayModel::with('task')
            ->where('user_id', auth()->id())
            ->where('amount', '>', 0)
            ->where('today', $day)
            ->orderBy('status', 'desc')
            ->orderBy('id', 'asc')->page();
    }

    public static function detail(int $id) {
        $model = TaskDayModel::findWithAuth($id);
        if (empty($model)) {
            throw new \Exception('不存在');
        }
        if ($model->task) {
            $model->task->children;
        }
        return $model;
    }

    public static function save(int $task_id, int $id = 0, int $amount = 1) {
        if ($id > 0) {
            $day = TaskDayModel::findWithAuth($id);
            if (empty($day)) {
                throw new \Exception('错误');
            }
            $day->amount = $amount;
            $day->save();
            return $day;
        }
        $task = TaskModel::findWithAuth($task_id);
        if (empty($task)) {
            throw new \Exception('任务不存在');
        }
        return static::add($task, $amount);
    }

    public static function add(TaskModel $task, int $amount = 1) {
        $day = date('Y-m-d');
        $model = TaskDayModel::where('task_id', $task->id)
            ->where('today', $day)->first();
        if (!empty($model)) {
            $model->amount += $amount;
            $model->save();
            return $model;
        }
        return TaskDayModel::create([
            'user_id' => $task->user_id,
            'task_id' => $task->id,
            'today' => $day,
            'amount' => $amount,
            'status' => TaskDayModel::STATUS_NONE
        ]);
    }

    public static function remove(int $id) {
        $day = TaskDayModel::findWithAuth($id);
        if (empty($day)) {
            throw new \Exception('任务不存在');
        }
        try {
            TaskRepository::stop($id);
        } catch (\Exception $ex) {}
        $day->delete();
    }
}