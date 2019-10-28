<?php
namespace Module\Task\Service;

use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskModel;

class TaskController extends Controller {

    public function indexAction() {
        $model_list = TaskModel::where('user_id', auth()->id())
            ->orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => 0]);
    }

    public function editAction($id) {
        $model = TaskModel::findOrNew($id);
        if ($id > 0 && $model->user_id !== auth()->id()) {
            return $this->redirectWithMessage('./task', '任务不存在');
        }
        if (empty($model->every_time)) {
            $model->every_time = 25;
        }
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new TaskModel();
        $model->user_id = auth()->id();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => url('./task')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        TaskModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => url('./task')
        ]);
    }

    public function todayAction($time = null) {
        if (empty($time)) {
            $time = date('Ymd');
        }
        $task_list = TaskModel::where('user_id', auth()->id())
            ->where('status', '<', 2)->get();
        if (empty($task_list)) {
            return $this->redirectWithMessage(
                './task/create', '请先添加任务'
            );
        }
        $model_list = TaskDayModel::with('task')
            ->where('user_id', auth()->id())
            ->where('today', $time)->page();
        return $this->show(compact('model_list', 'task_list'));
    }

    public function createDayAction() {
        return $this->runMethodNotProcess('editDay', ['id' => 0]);
    }

    public function editDayAction($id) {
        $model = TaskDayModel::findOrNew($id);
        $task_list = TaskModel::where('user_id', auth()->id())
            ->where('status', '<', 2)->get();
        return $this->show(compact('model', 'task_list'));
    }

    public function saveDayAction($task_id, $id = 0, $amount = 1) {
        $task = TaskModel::where('user_id', auth()->id())
            ->where('id', $task_id)->first();
        if (empty($task)) {
            return $this->jsonFailure('任务不存在');
        }
        if ($id > 0) {
            TaskDayModel::where('id', $id)->update([
                'task_id' => $task_id,
                'amount' => $amount
            ]);
        } else {
            TaskDayModel::add($task, $amount);
        }
        return $this->jsonSuccess([
            'url' => url('./task/today')
        ]);
    }

    public function deleteDayAction($id) {
        TaskDayModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => url('./task/today')
        ]);
    }

    public function toggleAction($id) {
        $day = TaskDayModel::where('id', $id)
            ->where('amount', '>', 0)
            ->where('today', date('Ymd'))->first();
        if (empty($day)) {
            return $this->jsonFailure('任务错误');
        }
        $model = TaskModel::find($day->task_id);
        if (empty($model)) {
            return $this->jsonFailure('任务错误');
        }
        if ($model->status == TaskModel::STATUS_COMPETE) {
            return $this->jsonFailure('任务已结束');
        }
        if ($model->status == TaskModel::STATUS_RUNNING) {
            $model->makeEnd();
            return $this->jsonSuccess($model);
        }
        $log = $model->makeNewRun();
        return $this->jsonSuccess($model, $log);
    }

    public function playAction($id) {
        $model = TaskModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('任务错误');
        }
        if ($model->status == TaskModel::STATUS_COMPETE) {
            return $this->jsonFailure('任务已结束');
        }
        if ($model->status == TaskModel::STATUS_RUNNING) {
            return $this->jsonSuccess($model);
        }
        $log = $model->makeNewRun();
        return $this->jsonSuccess($model);
    }

    public function pauseAction($id) {
        $model = TaskModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('任务错误');
        }
        if ($model->status == TaskModel::STATUS_COMPETE) {
            return $this->jsonFailure('任务已结束');
        }
        if ($model->status == TaskModel::STATUS_RUNNING) {
            $model->makeEnd();
        }
        return $this->jsonSuccess($model);
    }

    public function stopAction($id) {
        $model = TaskModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('任务错误');
        }
        if ($model->status == TaskModel::STATUS_COMPETE) {
            return $this->jsonSuccess($model->toArray());
        }
        if ($model->status == TaskModel::STATUS_RUNNING) {
            $model->makeEnd();
        }
        $model->status = TaskModel::STATUS_COMPETE;
        $model->save();
        return $this->jsonSuccess($model->toArray());
    }
}