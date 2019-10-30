<?php
namespace Module\Task\Service;

use Module\Task\Domain\Model\TaskDayModel;
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
            ->where('amount', '>', 0)
            ->orderBy('status', 'asc')->orderBy('id', 'asc')->get();
        return $this->show(compact('model_list'));
    }
}