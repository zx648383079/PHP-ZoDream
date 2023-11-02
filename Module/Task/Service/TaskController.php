<?php
namespace Module\Task\Service;

use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;
use Module\Task\Domain\Repositories\TaskRepository;

class TaskController extends Controller {

    public function indexAction() {
        $items = TaskModel::where('user_id', auth()->id())
            ->orderBy('status', 'desc')
            ->orderBy('id', 'desc')->page();
        return $this->show(compact('items'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = TaskModel::findOrNew($id);
        if ($id > 0 && $model->user_id !== auth()->id()) {
            return $this->redirectWithMessage('./task', '任务不存在');
        }
        if (empty($model->every_time)) {
            $model->every_time = 25;
        }
        return $this->show('edit', compact('model'));
    }

    public function saveAction(int $id, int $status = -1) {
        $data = request()
            ->get('name,every_time,description');
        $model = TaskModel::findOrNew($id);
        if ($id > 0 && $model->user_id !== auth()->id()) {
            return $this->renderFailure('任务不存在');
        }
        $model->set($data)->user_id = auth()->id();
        if ($status !== -1 && $model->status === TaskModel::STATUS_COMPLETE
        && $status != TaskModel::STATUS_COMPLETE) {
            $model->status = TaskModel::STATUS_NONE;
        }
        if ($model->save()) {
            return $this->renderData([
                'url' => url('./task')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction(int $id, bool $stop = false) {
        try {
            TaskRepository::stopTask($id);
        } catch (\Exception $ex) {}
        if (!$stop) {
            TaskModel::where('id', $id)->delete();
        }
        return $this->renderData([
            'url' => url('./task')
        ]);
    }

    public function todayAction($time = null) {
        if (empty($time)) {
            $time = date('Y-m-d');
        }
        $task_list = TaskRepository::getActiveTask();
        if (empty($task_list)) {
            return $this->redirectWithMessage(
                './task/create', '请先添加任务'
            );
        }
        $items = TaskDayModel::with('task')
            ->where('user_id', auth()->id())
            ->where('today', $time)
            ->orderBy('status', 'desc')
            ->orderBy('id', 'desc')
            ->page();
        return $this->show(compact('items', 'task_list'));
    }

    public function createDayAction() {
        return $this->editDayAction(0);
    }

    public function editDayAction(int $id) {
        $model = TaskDayModel::findOrNew($id);
        $task_list = TaskRepository::getActiveTask();
        if (empty($task_list)) {
            return $this->redirectWithMessage(
                './task/create', '请先添加任务'
            );
        }
        if ($model->isNewRecord) {
            $model->amount = 1;
        }
        return $this->show('editDay', compact('model', 'task_list'));
    }

    public function saveDayAction(int $task_id, int $id = 0, int $amount = 1) {
        $task = TaskModel::where('user_id', auth()->id())
            ->where('id', $task_id)->first();
        if (empty($task)) {
            return $this->renderFailure('任务不存在');
        }
        if ($id > 0) {
            TaskDayModel::where('id', $id)->update([
                'task_id' => $task_id,
                'amount' => $amount
            ]);
        } else {
            TaskDayModel::add($task, $amount);
        }
        return $this->renderData([
            'url' => url('./task/today')
        ]);
    }

    public function deleteDayAction(int $id) {
        try {
            TaskRepository::stop($id);
        } catch (\Exception $ex) {}
        TaskDayModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => url('./task/today')
        ]);
    }


    public function playAction(int $id) {
        try {
            $day = TaskRepository::start($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($day);
    }

    public function pauseAction(int $id) {
        try {
            $day = TaskRepository::pause($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($day);
    }

    public function stopAction(int $id) {
        try {
            $day = TaskRepository::stop($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($day);
    }

    public function checkAction(int $id) {
        try {
            $day = TaskRepository::check($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        if ($day === false) {
            return $this->renderData($day);
        }
        return $this->renderData($day, TaskRepository::restTip($day));
    }

    public function batchAddAction(int $id) {
        $task_list = TaskModel::where('user_id', auth()->id())
            ->whereIn('id', (array)$id)->get();
        if (empty($task_list)) {
            return $this->renderFailure('请选择任务');
        }
        foreach ($task_list as $item) {
            TaskDayModel::add($item, 1);
        }
        return $this->renderData(true);
    }

    public function stopTaskAction(int $id) {
        try {
            $day = TaskDayModel::findWithAuth($id);
            if (empty($day)) {
                return $this->renderFailure('任务不存在');
            }
            if ($day->status != TaskDayModel::STATUS_NONE) {
                return $this->renderFailure('正在进行中的任务无法停止');
            }
            $task = TaskRepository::stopTask($day->task_id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($task);
    }
}