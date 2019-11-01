<?php
namespace Module\Task\Service;

use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;
use Module\Task\Domain\Repositories\TaskRepository;

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

    public function saveAction($id, $status = false) {
        $data = app('request')
            ->get('name,every_time,description');
        $model = TaskModel::findOrNew($id);
        if ($id > 0 && $model->user_id !== auth()->id()) {
            return $this->jsonFailure('任务不存在');
        }
        $model->set($data)->user_id = auth()->id();
        if ($status !== false && $model->status === TaskModel::STATUS_COMPLETE
        && $status != TaskModel::STATUS_COMPLETE) {
            $model->status = 0;
        }
        if ($model->save()) {
            return $this->jsonSuccess([
                'url' => url('./task')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        try {
            TaskRepository::stopTask($id);
        } catch (\Exception $ex) {}
        TaskModel::where('id', $id)->delete();
        return $this->jsonSuccess([
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
        $task_list = TaskRepository::getActiveTask();
        if (empty($task_list)) {
            return $this->redirectWithMessage(
                './task/create', '请先添加任务'
            );
        }
        if ($model->isNewRecord) {
            $model->amount = 1;
        }
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
        try {
            TaskRepository::stop($id);
        } catch (\Exception $ex) {}
        TaskDayModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => url('./task/today')
        ]);
    }


    public function playAction($id) {
        try {
            $day = TaskRepository::start($id);
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess($day);
    }

    public function pauseAction($id) {
        try {
            $day = TaskRepository::pause($id);
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess($day);
    }

    public function stopAction($id) {
        try {
            $day = TaskRepository::stop($id);
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess($day);
    }

    public function checkAction($id) {
        try {
            $day = TaskRepository::check($id);
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        if ($day === false) {
            return $this->jsonSuccess($day);
        }
        // 记录今天完成的任务次数，每4轮多休息
        $count = TaskLogModel::where('created_at', '>',
            strtotime(date('Y-m-d 00:00:00')))
            ->where('status', TaskLogModel::STATUS_FINISH)
            ->where('user_id', auth()->id())
            ->count();
        $tip = '本轮任务完成，请休息3-5分钟';
        if ($count % 4 === 0) {
            $tip = '本轮任务完成，请休息20-25分钟';
        }
        return $this->jsonSuccess($day, $tip);
    }

    public function batchAddAction($id) {
        $task_list = TaskModel::where('user_id', auth()->id())
            ->whereIn('id', (array)$id)->get();
        if (empty($task_list)) {
            return $this->jsonFailure('请选择任务');
        }
        foreach ($task_list as $item) {
            TaskDayModel::add($item, 1);
        }
        return $this->jsonSuccess();
    }

    public function stopTaskAction($id) {
        try {
            $day = TaskDayModel::findWithAuth($id);
            if (empty($day)) {
                return $this->jsonFailure('任务不存在');
            }
            if ($day->status != TaskDayModel::STATUS_NONE) {
                return $this->jsonFailure('正在进行中的任务无法停止');
            }
            $task = TaskRepository::stopTask($day->task_id);
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess($task);
    }
}