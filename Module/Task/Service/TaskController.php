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

    public function toggleAction($id) {
        $model = TaskModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('任务错误');
        }
        if ($model->status == TaskModel::STATUS_COMPETE) {
            return $this->jsonFailure('任务已结束');
        }
        if ($model->status == TaskModel::STATUS_RUNNING) {
            $model->makeEnd();
            return $this->jsonSuccess($model);
        }
        $log = $model->makeNewRun();
        return $this->jsonSuccess($model, $log);
    }

    public function playAction($id) {
        $model = TaskModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('任务错误');
        }
        if ($model->status == TaskModel::STATUS_COMPETE) {
            return $this->jsonFailure('任务已结束');
        }
        if ($model->status == TaskModel::STATUS_RUNNING) {
            return $this->jsonSuccess($model);
        }
        $log = $model->makeNewRun();
        return $this->jsonSuccess($model);
    }

    public function pauseAction($id) {
        $model = TaskModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('任务错误');
        }
        if ($model->status == TaskModel::STATUS_COMPETE) {
            return $this->jsonFailure('任务已结束');
        }
        if ($model->status == TaskModel::STATUS_RUNNING) {
            $model->makeEnd();
        }
        return $this->jsonSuccess($model);
    }

    public function stopAction($id) {
        $model = TaskModel::find($id);
        if (empty($model)) {
            return $this->jsonFailure('任务错误');
        }
        if ($model->status == TaskModel::STATUS_COMPETE) {
            return $this->jsonSuccess($model->toArray());
        }
        if ($model->status == TaskModel::STATUS_RUNNING) {
            $model->makeEnd();
        }
        $model->status = TaskModel::STATUS_COMPETE;
        $model->save();
        return $this->jsonSuccess($model->toArray());
    }
}