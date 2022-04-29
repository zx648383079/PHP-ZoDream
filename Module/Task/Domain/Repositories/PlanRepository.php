<?php
declare(strict_types=1);
namespace Module\Task\Domain\Repositories;

use Module\Task\Domain\Model\TaskModel;
use Module\Task\Domain\Model\TaskPlanModel;

final class PlanRepository {

    public static function getList(int $type)
    {
        return TaskPlanModel::with('task')
            ->where('plan_type', $type)
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')->page();
    }


    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = TaskPlanModel::findOrNew($id);
        $model->load($data);
        $task = TaskModel::findWithAuth($model->task_id);
        if (empty($task)) {
            throw new \Exception('任务错误');
        }
        if ($model->plan_type < 1) {
            $model->plan_time = is_numeric($model->plan_time) ? intval($model->plan_time) : strtotime($model->plan_time);
        } else {
            $model->plan_time = max(intval($model->plan_time), 1);
        }
        $model->user_id = auth()->id();
        $other = TaskPlanModel::where('id', '<>', $id)
            ->where('user_id', auth()->id())->where('task_id', $model->task_id)
            ->where('plan_type', $model->plan_type)->first();
        if (!empty($other)) {
            $other->amount += $model->amount;
            $other->plan_time = $model->plan_time;
            $model = $other;
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        if ($id > 0 && $model->id != $id) {
            static::remove($id);
        }
        $model->task = $task;
        return $model;
    }

    public static function remove(int $id) {
        TaskPlanModel::where('id', $id)
            ->where('user_id', auth()->id())->delete();
    }
}