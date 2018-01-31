<?php
namespace Module\WeChat\Service;

use Module\ModuleController;
use Module\WeChat\Domain\Model\ReplyModel;

class ReplyController extends ModuleController {
    public function indexAction($event = null) {
        $reply_list = ReplyModel::when(!empty($event), function ($query) use ($event) {
            $query->where('event', $event);
        })->page();
        return $this->show(compact('reply_list'));
    }

    public function addAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ReplyModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new ReplyModel();
        $model->wid = '1';
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess($model);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        ReplyModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}