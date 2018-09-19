<?php
namespace Module\Task\Service;

class HomeController extends Controller {

    public function indexAction() {
        $model_list = TaskModel::where('status', 0)
            ->orderBy('updated_at', 'desc')->page();
        return $this->show(compact('model_list'));
    }
}