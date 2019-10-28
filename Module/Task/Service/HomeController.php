<?php
namespace Module\Task\Service;

use Module\Task\Domain\Model\TaskDayModel;

class HomeController extends Controller {

    public function indexAction() {
        $model_list = TaskDayModel::with('task')
            ->where('user_id', auth()->id())
            ->where('amount', '>', 0)
            ->orderBy('status', 'asc')->orderBy('id', 'asc')->get();
        return $this->show(compact('model_list'));
    }
}