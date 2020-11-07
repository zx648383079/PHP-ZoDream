<?php
namespace Module\Task\Service\Api;

use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;
use Module\Task\Domain\Repositories\DayRepository;
use Module\Task\Domain\Repositories\TaskRepository;
use Zodream\Infrastructure\Http\Request;
use Zodream\Route\Controller\RestController;
use Zodream\Validate\ValidationException;

class HomeController extends RestController {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction(int $status = 0, int $id = 0, int $parent_id = 0, string $keywords = '') {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $data = TaskRepository::getList($keywords, $status, $parent_id);
        return $this->renderPage($data);
    }

    public function detailAction($id) {
        try {
            $model = TaskRepository::detail($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request, $id = 0, $status = false) {
        try {
            $data = $request->validate([
                'parent_id' => 'int',
                'name' => 'required|string:0,100',
                'description' => 'string:0,255',
                'every_time' => 'int:0,9999',
                'space_time' => 'int:0,127',
                'duration' => 'int:0,127',
                'start_at' => 'int',
            ]);
            $model = TaskRepository::save($data, $id, $status);
        } catch (ValidationException $ex) {
            return $this->renderFailure($ex->validator->firstError());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction($id, $stop = false) {
        try {
            TaskRepository::remove($id, $stop);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function todayAction($time = null) {
        if (empty($time)) {
            $time = date('Y-m-d');
        }
        $data = DayRepository::getList($time);
        return $this->renderPage($data);
    }

    public function detailDayAction($id) {
        try {
            $model = DayRepository::detail($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveDayAction($task_id, $id = 0, $amount = 1) {
        try {
            $model = DayRepository::save($task_id, $id, $amount);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteDayAction($id) {
        try {
            DayRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }


    public function playAction($id, $child_id = 0) {
        try {
            $day = TaskRepository::start($id, $child_id);
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

    public function batchStopTaskAction($id) {
        try {
            foreach ((array)$id as $item) {
                if ($item < 1) {
                    continue;
                }
                TaskRepository::stopTask($item);
            }
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render(['data' => true]);
    }
}