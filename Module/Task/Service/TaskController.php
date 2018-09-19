<?php
namespace Module\Task\Service;

use Module\Task\Domain\Model\TaskModel;

class TaskController extends Controller {

    public function indexAction() {
        $model_list = TaskModel::orderBy('id', 'desc')->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => 0]);
    }

    public function editAction($id) {
        $model = TaskModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new TaskModel();
        $model->user_id = auth()->id();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => url('./')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        TaskModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => url('./')
        ]);
    }
}