<?php
namespace Module\Task\Service\Api;

use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;
use Module\Task\Domain\Repositories\TaskRepository;
use Zodream\Route\Controller\RestController;

class HomeController extends RestController {

    public function indexAction(int $status = 0, int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $data = TaskModel::where('user_id', auth()->id())
            ->when($status > 0, function ($query) use ($status) {
                if ($status > 1) {
                    return $query->where('status', TaskModel::STATUS_COMPLETE);
                }
                return $query->where('status', '>=', 5);
            })
            ->orderBy('id', 'desc')->page();
        return $this->renderPage($data);
    }

    public function detailAction($id) {
        $model = TaskModel::findOrNew($id);
        if ($id > 0 && $model->user_id !== auth()->id()) {
            return $this->renderFailure('任务不存在');
        }
        if (empty($model->every_time)) {
            $model->every_time = 25;
        }
        return $this->render($model);
    }

    public function saveAction($id = 0, $status = false) {
        $data = app('request')
            ->get('name,every_time,description');
        $model = TaskModel::findOrNew($id);
        if ($id > 0 && $model->user_id !== auth()->id()) {
            return $this->renderFailure('任务不存在');
        }
        $model->set($data)->user_id = auth()->id();
        if ($status !== false && $model->status === TaskModel::STATUS_COMPLETE
        && $status != TaskModel::STATUS_COMPLETE) {
            $model->status = 0;
        }
        if ($model->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id, $stop = false) {
        try {
            TaskRepository::stopTask($id);
        } catch (\Exception $ex) {}
        if (!$stop) {
            TaskModel::where('id', $id)->delete();
        }
        return $this->render([
            'data' => true
        ]);
    }

    public function todayAction($time = null) {
        if (empty($time)) {
            $time = date('Y-m-d');
        }
        $data = TaskDayModel::with('task')
            ->where('user_id', auth()->id())
            ->where('today', $time)->page();
        return $this->renderPage($data);
    }

    public function detailDayAction($id) {
        $model = TaskDayModel::findOrNew($id);
        if ($model->isNewRecord) {
            $model->amount = 1;
        }
        return $this->render($model);
    }

    public function saveDayAction($task_id, $id = 0, $amount = 1) {
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
        return $this->render(['data' => true]);
    }

    public function deleteDayAction($id) {
        try {
            TaskRepository::stop($id);
        } catch (\Exception $ex) {}
        TaskDayModel::where('id', $id)->delete();
        return $this->render(['data' => true]);
    }


    public function playAction($id) {
        try {
            $day = TaskRepository::start($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($day);
    }

    public function pauseAction($id) {
        try {
            $day = TaskRepository::pause($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($day);
    }

    public function stopAction($id) {
        try {
            $day = TaskRepository::stop($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($day);
    }

    public function checkAction($id) {
        try {
            $day = TaskRepository::check($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        if ($day === false) {
            return $this->render(['data' => $day]);
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
        return $this->render(array_merge($day->toArray(), compact('tip')));
    }

    public function batchAddAction($id) {
        $task_list = TaskModel::where('user_id', auth()->id())
            ->whereIn('id', (array)$id)->get();
        if (empty($task_list)) {
            return $this->renderFailure('请选择任务');
        }
        foreach ($task_list as $item) {
            TaskDayModel::add($item, 1);
        }
        return $this->render(['data' => true]);
    }

    public function stopTaskAction($id) {
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
        return $this->render($task);
    }
}