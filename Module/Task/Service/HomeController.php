<?php
namespace Module\Task\Service;

use Module\Task\Domain\Model\TaskDayModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Repositories\TaskRepository;

class HomeController extends Controller {

    public function indexAction() {
        $task_list = TaskRepository::getActiveTask();
        return $this->show(compact('task_list'));
    }

    public function panelAction() {
        $this->layout = false;
        $model_list = TaskDayModel::with('task')
            ->where('user_id', auth()->id())
            ->where('today', date('Y-m-d'))
            ->where('amount', '>', 0)
            ->orderBy('status', 'asc')
            ->orderBy('id', 'asc')->get();
        $last_log = TaskLogModel::where('user_id', auth()->id())
            ->where('status', '>', 1)
            ->where('end_at', '>', time() - 3600)
            ->first();
        return $this->show(compact('model_list', 'last_log'));
    }
}