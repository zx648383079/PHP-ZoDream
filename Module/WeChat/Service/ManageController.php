<?php
namespace Module\WeChat\Service;

use Module\ModuleController;
use Module\WeChat\Domain\Model\WeChatModel;

class ManageController extends ModuleController {
    public function indexAction() {
        $model_list = WeChatModel::all();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = WeChatModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new WeChatModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess($model);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        WeChatModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}