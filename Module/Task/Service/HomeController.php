<?php
namespace Module\Task\Service;

use Module\Task\Domain\Model\TaskModel;

class HomeController extends Controller {

    public function indexAction() {
        $model_list = TaskModel::whereIn('status', [TaskModel::STATUS_NONE, TaskModel::STATUS_RUNNING])
            ->orderBy('updated_at', 'desc')->page();
        return $this->show(compact('model_list'));
    }
}