<?php
declare(strict_types=1);
namespace Module\Task\Service\Api;

use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;
use Module\Task\Domain\Repositories\DayRepository;
use Module\Task\Domain\Repositories\TaskRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Validate\ValidationException;

class HomeController extends Controller {

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

    public function detailAction(int $id) {
        try {
            $model = TaskRepository::detail($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request, int $id = 0, int $status = -1) {
        try {
            $data = $request->validate([
                'parent_id' => 'int',
                'name' => 'required|string:0,100',
                'description' => 'string:0,255',
                'every_time' => 'int:0,9999',
                'space_time' => 'int:0,127',
                'duration' => 'int:0,127',
                'start_at' => '',
            ]);
            $model = TaskRepository::save($data, $id, $status);
        } catch (ValidationException $ex) {
            return $this->renderFailure($ex->bag->firstError());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function fastCreateAction(Request $request) {
        try {
            $data = $request->validate([
                'parent_id' => 'int',
                'name' => 'required|string:0,100',
                'description' => 'string:0,255',
                'every_time' => 'int:0,9999',
                'space_time' => 'int:0,127',
                'duration' => 'int:0,127',
                'start_at' => '',
            ]);
            $task = TaskRepository::save($data);
            $model = DayRepository::save($task->id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id, bool $stop = false) {
        try {
            TaskRepository::remove($id, $stop);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function todayAction(string $time = '') {
        if (empty($time)) {
            $time = date('Y-m-d');
        }
        $data = DayRepository::getList($time);
        return $this->renderPage($data);
    }

    public function detailDayAction(int $id) {
        try {
            $model = DayRepository::detail($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveDayAction(int $task_id, int $id = 0, int $amount = 1) {
        try {
            $model = DayRepository::save($task_id, $id, $amount);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteDayAction(int $id) {
        try {
            DayRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }


    public function playAction(int $id, int $child_id = 0) {
        try {
            $day = TaskRepository::start($id, $child_id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($day);
    }

    public function pauseAction(int $id) {
        try {
            $day = TaskRepository::pause($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($day);
    }

    public function stopAction(int $id) {
        try {
            $day = TaskRepository::stop($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($day);
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

    public function batchAddAction(array|int $id) {
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
        return $this->render($task);
    }

    public function batchStopTaskAction(int|array $id) {
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
        return $this->renderData(true);
    }
}